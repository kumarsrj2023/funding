<?php

namespace App\Http\Controllers;

use App\Models\BusinessInfo;
use App\Models\LoanInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PipelineController extends Controller
{
    public function bcaPipeline(Request $request)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if ($request->isMethod('GET')) {

            // $response = $this->getBCAMilestoneData();
            $response = $this->getMilestoneData(['SIF', 'sif'], true);

            // return $bcaTypeLoans;
            return view('bca-pipeline.list')->with(compact('response'));
        }

        if ($request->ajax()) {

            if (isset($request->id) && !empty($request->id)) {

                $rules = [
                    'milestone' => 'required',
                    'loan_type' => 'required'
                ];
                $attributes = [
                    'milestone' => 'Milestone',
                    'loan_type' => 'Loan Type'
                ];

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($attributes);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }

                try {
                    // update data
                    LoanInfo::where('id', $request->id)->update(
                        [
                            'milestone' => $request->milestone,
                            'sub_milestone' => $request->sub_milestone,
                            'analyst' => $request->analyst,
                            'loan_type' => $request->loan_type,
                            'description' => $request->description
                        ]
                    );

                    $response = $this->getMilestoneData(['SIF', 'sif'], true);

                    return response()->json(['status' => true, 'bcaTypeLoans' => $response['nestedLoans']]);
                } catch (\Exception $e) {
                    // die($e);
                    Log::error('Error From Deal Pipeline page: ' . $e->getMessage());
                    return response()->json(['status' => false, 'message' => 'An error occurred while processing your request.', 'error_log' => $e->getMessage()], 500);
                }
            }
        }
    }

    public function sifPipeline(Request $request)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if ($request->isMethod('GET')) {

            $response = $this->getMilestoneData(['SIF', 'sif']);

            return view('sif-pipeline.list')->with(compact('response'));
        }

        if ($request->ajax()) {

            if (isset($request->id) && !empty($request->id)) {
                $rules = [
                    'milestone' => 'required',
                    // 'sub_milestone' => 'required',
                    // 'analyst' => 'required',
                    'loan_type' => 'required'
                ];
                $attributes = [
                    'milestone' => 'Milestone',
                    'sub_milestone' => 'Sub Milestone',
                    'analyst' => 'Underwriter',
                    'loan_type' => 'Loan Type'
                ];

                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($attributes);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }

                try {
                    // update data
                    LoanInfo::where('id', $request->id)->update(
                        [
                            'milestone' => $request->milestone,
                            'sub_milestone' => $request->sub_milestone,
                            'analyst' => $request->analyst,
                            'loan_type' => $request->loan_type,
                            'description' => $request->description
                        ]
                    );

                    $response = $this->getMilestoneData(['SIF', 'sif']);

                    return response()->json(['status' => true, 'sifTypeLoans' => $response['nestedLoans']]);
                } catch (\Exception $e) {
                    // die($e);
                    Log::error('Error From Deal Pipeline page: ' . $e->getMessage());
                    return response()->json(['status' => false, 'message' => 'An error occurred while processing your request.', 'error_log' => $e->getMessage()], 500);
                }
            }
        }
    }

    private function getMilestoneData(array $loanTypeFilter, bool $exclude = false)
    {
        // Determine the query based on inclusion or exclusion of the loan types
        $loanTypeQuery = LoanInfo::query();
        if ($exclude) {
            $loanTypeQuery->whereNotIn('loan_type', $loanTypeFilter);
        } else {
            $loanTypeQuery->whereIn('loan_type', $loanTypeFilter);
        }

        // Fetch Loans with Necessary Joins and Filtered Data
        $loanTypeLoans = $loanTypeQuery
            ->whereNotNull('milestone')
            ->where('milestone', '<>', '')
            ->join('wp_business_info', 'wp_loan_info.user_id', '=', 'wp_business_info.user_id')
            ->leftJoin('wp_introducers_info', 'wp_loan_info.broker_id', '=', 'wp_introducers_info.id')
            ->orderBy('wp_business_info.business_name', 'asc')
            ->select(
                'wp_loan_info.*',
                'wp_business_info.business_name as business_name',
                'wp_business_info.id as business_id',
                'wp_introducers_info.broker_name as introducer'
            )
            ->get();

        // Fetch Milestone Stages and Sub Stages
        $bcaMilestoneStages = DB::table('wp_BCA_milestone_stages')->get();
        $sifMilestoneStages = DB::table('wp_SIF_milestone_stages')->get();
        $bcaSubStages = DB::table('wp_BCA_milestone_sub_stages')->get();
        $sifSubStages = DB::table('wp_SIF_milestone_sub_stages')->get();

        // Determine the milestone stages and groupings based on loan type
        if (!$exclude && $loanTypeFilter === ['SIF', 'sif']) {
            $milestoneStages = $sifMilestoneStages;
        } else {
            $milestoneStages = $bcaMilestoneStages;
        }

        // Group loans by milestone
        $nestedLoans = [];
        foreach ($milestoneStages as $stage) {
            $nestedLoans[$stage->milestone] = [
                'stage_id' => $stage->id,
                'loans' => [],
            ];
        }

        // Group loans under their respective milestones
        foreach ($loanTypeLoans as $loan) {
            if (isset($nestedLoans[$loan->milestone])) {
                $nestedLoans[$loan->milestone]['loans'][] = [
                    'id' => $loan->id,
                    'loan_type' => $loan->loan_type,
                    'analyst' => $loan->analyst,
                    'advance_requested' => $loan->advance_requested,
                    'milestone' => $loan->milestone,
                    'sub_milestone' => $loan->sub_milestone,
                    'business_name' => $loan->business_name,
                    'business_id' => $loan->business_id,
                    'description' => $loan->description,
                    'introducer' => $loan->introducer ?? '',
                ];
            }
        }

        // Prepare the Response
        return [
            'nestedLoans' => $nestedLoans,
            'bcaMilestoneStages' => $bcaMilestoneStages,
            'sifMilestoneStages' => $sifMilestoneStages,
            'bcaSubStages' => $bcaSubStages,
            'sifSubStages' => $sifSubStages,
        ];
    }
}
