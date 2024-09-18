<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DDControlForm;
use DataTables;
use Storage;
use \Carbon\Carbon;
use DB;
use Excel;
use Helper;

class DDControlController extends Controller
{
    public function add(Request $request)
    {
        $data = null;

        $data = DDControlForm::where('user_id', auth()->user()->ID)->first();

        if (request()->method() == 'GET')
        {
            return view('dd-control.add')->with(compact('data'));
        }

        $not_created_yet = false;

        if (empty($data))
        {
            $not_created_yet = true;
            $data = new DDControlForm();
            $data->user_id = auth()->user()->ID;
        }

        if (strtoupper($request->group_transaction) == 'YES')
        {
            $data->group_transaction = 1;

            $data->add_company_1 = isset($request->add_company_1) && !empty($request->add_company_1) ? $request->add_company_1 : null;
            $data->add_company_2 = isset($request->add_company_2) && !empty($request->add_company_2) ? $request->add_company_2 : null;
            $data->add_company_3 = isset($request->add_company_3) && !empty($request->add_company_3) ?$request->add_company_3 : null ;
            $data->add_company_4 = isset($request->add_company_4) && !empty($request->add_company_4) ? $request->add_company_4 : null ;
        }
        else
        {
            $data->group_transaction = 0;
            $data->add_company_1 = null;
            $data->add_company_2 = null;
            $data->add_company_3 = null;
            $data->add_company_4 = null;
        }

        $data->project_ref = isset($request->project_ref) && !empty($request->project_ref) ? $request->project_ref : null;

        $data->deal_date = isset($request->deal_date) && !empty($request->deal_date) ? $request->deal_date : null;

        if (strtoupper($request->ascending_order) == 'YES')
        {
            $data->ascending_order = 1;
        }
        else
        {
            $data->ascending_order = 0;
        }

        $data->rev_exp_cutt_off = isset($request->rev_exp_cutt_off) && !empty($request->rev_exp_cutt_off) ? $request->rev_exp_cutt_off : null;

        $data->currency = isset($request->currency) && !empty($request->currency) ? strtoupper($request->currency) : null;

        $data->usd_to_gbp = isset($request->usd_to_gbp) && !empty($request->usd_to_gbp) ? strtoupper($request->usd_to_gbp) : 0;

        $data->eur_to_gbp = isset($request->eur_to_gbp) && !empty($request->eur_to_gbp) ? strtoupper($request->eur_to_gbp) : 0;

        if (strtoupper($request->open_banking) == 'YES')
        {
            $data->open_banking = 1;
        }
        else
        {
            $data->open_banking = 0;
        }

        if (strtoupper($request->limit_date_range) == 'YES')
        {
            $data->limit_date_range = 1;

            if (strtoupper($request->date_range_is_fixed) == 'YES')
            {
                $data->date_range_is_fixed = 1;
            }
            else
            {
                $data->date_range_is_fixed = 0;
            }

            $data->start_date = isset($request->start_date) && !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : null;

            $data->end_date = isset($request->end_date) && !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : null;

            $data->last_number_of_days = isset($request->last_number_of_days) && !empty($request->last_number_of_days) ? $request->last_number_of_days : null;

        }
        else
        {
            $data->limit_date_range = 0;
        }

        if (strtoupper($request->manual_review) == 'YES')
        {
            $data->manual_review = 1;
        }
        else
        {
            $data->manual_review = 0;
        }

        $data->card_repayment = isset($request->card_repayment) && !empty($request->card_repayment) && $request->card_repayment == 1 ? 1 : 0;

        $data->save();

        if ($not_created_yet)
        {
            return redirect()->back()->with('message', 'New DD Control created for ' . auth()->user()->display_name);
        }

        return redirect()->back()->with('message', 'DD Control ' . auth()->user()->display_name . ' updated');
    }
}
