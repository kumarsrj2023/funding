<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerInfo;
use App\Models\BusinessInfo;
use App\Models\DirectorInfo;
use App\Models\LoanInfo;
use DataTables;
use Storage;
use \Carbon\Carbon;
use DB;
use Excel;
use Helper;

class CAISController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax())
        {
            $data = DB::table('wp_business_info')
                ->join('wp_customer_info', 'wp_customer_info.user_id', '=', 'wp_business_info.user_id')
                ->leftJoin('wp_loan_info', 'wp_loan_info.user_id', '=', 'wp_business_info.user_id')
                ->leftJoin('ga_credit_safe', 'ga_credit_safe.id', '=', 'wp_business_info.ga_credit_safe_id')
                ->select('wp_business_info.*', 'wp_customer_info.id as customer_info_id', 'wp_customer_info.first_name', 'wp_customer_info.middle_name', 'wp_customer_info.last_name', 'wp_customer_info.email', 'wp_customer_info.phone', 'wp_customer_info.role_in_business', 'ga_credit_safe.compSum_businessName', 'wp_loan_info.loan_number', 'wp_loan_info.special_instruction_indicator', 'wp_loan_info.flag_settings', 'wp_loan_info.mortgage_flags', 'wp_loan_info.transfered_to_collection_account', 'wp_loan_info.default_satisfaction_date', 'wp_loan_info.original_default_balance', 'wp_loan_info.id as loan_info_id')
                ->groupBy('wp_loan_info.id');
                

            return DataTables::of($data)
                    ->addColumn('index_data', function ($row) {

                        $checked = "";

                        if (isset($row->is_selected) && !empty($row->is_selected)) 
                        {
                            $checked = 'checked=""';
                        }

                        return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox update-value-cais-checkbox" data-name="is_selected" id="select-item-'. $row->id .'" value="'. $row->id .'" '. $checked .'>
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                </div>';
                        
                    })
                    ->editColumn('business_name', function ($row) {

                        if (!empty($row->business_name))
                        {
                            return $row->business_name;
                        }

                        if (!empty($row->compSum_businessName))
                        {
                            return $row->compSum_businessName;
                        }

                        return '-';
                        
                    })
                    ->editColumn('loan_number', function ($row) {

                        return !empty($row->loan_number) ? $row->loan_number : '-';
                        
                    })
                    ->editColumn('name_change', function ($row) {

                        $n_selected = isset($row->name_change) && !empty($row->name_change) && $row->name_change == 'N' ? 'selected' : '';
                        $y_selected = isset($row->name_change) && !empty($row->name_change) && $row->name_change == 'Y' ? 'selected' : '';

                        $name_change = '<select name="[name_change'. $row->loan_info_id .']" data-name="name_change" class="name_change_' . $row->loan_info_id .' update-value-cais">
                                            <option value="N" '. $n_selected .'>N</option>
                                            <option value="Y" '. $y_selected .'>Y</option>
                                        </select>';
                        
                        return $name_change;
                        
                    })
                    ->editColumn('previous_name_and_address', function ($row) {

                        $previous_name_and_address_value = isset($row->previous_name_and_address) && !empty($row->previous_name_and_address) ? $row->previous_name_and_address : 'N';

                        $previous_name_and_address = '<input type="text" name="previous_name_and_address[' . $row->loan_info_id . ']" data-name="previous_name_and_address" class="previous_name_and_address_' . $row->loan_info_id . ' update-value-cais w-100px text-center" value="' . $previous_name_and_address_value . '">';

                        return $previous_name_and_address;
                        
                    })
                    ->editColumn('special_instruction_indicator', function ($row) {

                        if (isset($row->special_instruction_indicator) && !empty($row->special_instruction_indicator))
                        {
                            $a_selected = $row->special_instruction_indicator == 'A' ? 'selected' : ''; 
                            $d_selected = $row->special_instruction_indicator == 'D' ? 'selected' : ''; 
                            $l_selected = $row->special_instruction_indicator == 'L' ? 'selected' : ''; 
                        }
                        else
                        {
                            $a_selected = $d_selected = $l_selected = '';
                        }

                        $special_instruction_indicator = '<select name="special_instruction_indicator[' . $row->loan_info_id .']" class="special_instruction_indicator_' . $row->loan_info_id .' update-value-cais" data-name="special_instruction_indicator">
                            <option value="">Select</option>
                            <option value="A" '. $a_selected .'>A</option>
                            <option value="D" '. $d_selected .'>D</option>
                            <option value="L" '. $l_selected .'>L</option>
                        </select>';

                        return $special_instruction_indicator;
                        
                    })
                    ->editColumn('flag_settings', function ($row) {

                        $flag_settings = '<select name="flag_settings[' . $row->loan_info_id . ']" class="flag_settings_' . $row->loan_info_id . ' update-value-cais" data-name="flag_settings">';
                        $flag_settings .= '<option value="">Select</option>';
                        
                        $options = ['D', 'T', 'F', 'K', 'H', 'J', 'N', 'E', 'L', 'P', 'C', 'S', 'G', 'R', 'V', 'A', 'M', 'I', 'Q'];

                        foreach ($options as $value => $label)
                        {
                            $selected = isset($row->flag_settings) && !empty($row->flag_settings) && $row->flag_settings == $label ? 'selected' : '';
                            $flag_settings .= '<option value="' . $label . '" ' . $selected . '>' . $label . '</option>';
                        }

                        $flag_settings .= '</select>';

                        return $flag_settings;
                    })
                    ->editColumn('mortgage_flags', function ($row) {

                        $mortgage_flags = '<select name="mortgage_flags[' . $row->loan_info_id . ']" class="mortgage_flags_' . $row->loan_info_id . ' update-value-cais" data-name="mortgage_flags">';
                        $mortgage_flags .= '<option value="">Select</option>';
                        
                        $options = ['A', 'C', 'G'];

                        foreach ($options as $value => $label)
                        {
                            $selected = isset($row->mortgage_flags) && !empty($row->mortgage_flags) && $row->mortgage_flags == $label ? 'selected' : '';
                            $mortgage_flags .= '<option value="' . $label . '" ' . $selected . '>' . $label . '</option>';
                        }

                        $mortgage_flags .= '</select>';

                        return $mortgage_flags;
                    })
                    ->editColumn('transfered_to_collection_account', function ($row) {

                        $transfered_to_collection_account = '<select name="transfered_to_collection_account[' . $row->loan_info_id . ']" class="transfered_to_collection_account_' . $row->loan_info_id . ' update-value-cais" data-name="transfered_to_collection_account">';
                        $transfered_to_collection_account .= '<option value="">Select</option>';
                        
                        $options = ['Y'];

                        foreach ($options as $value => $label)
                        {
                            $selected = isset($row->transfered_to_collection_account) && !empty($row->transfered_to_collection_account) && $row->transfered_to_collection_account == $label ? 'selected' : '';
                            $transfered_to_collection_account .= '<option value="' . $label . '" ' . $selected . '>' . $label . '</option>';
                        }

                        $transfered_to_collection_account .= '</select>';

                        return $transfered_to_collection_account;
                    })
                    ->editColumn('default_satisfaction_date', function ($row) {

                        $default_satisfaction_date_value = isset($row->default_satisfaction_date) && !empty($row->default_satisfaction_date) ? date('Y-m-d', strtotime($row->default_satisfaction_date)) : '';

                        $default_satisfaction_date = '<input type="date" name="default_satisfaction_date[' . $row->loan_info_id . ']" class="default_satisfaction_date_' . $row->loan_info_id . ' w-100px update-value-cais text-center" value="' . $default_satisfaction_date_value . '" data-name="default_satisfaction_date" />';

                        return $default_satisfaction_date;
                    })
                    ->editColumn('original_default_balance', function ($row) {

                        $original_default_balance_value = isset($row->original_default_balance) && !empty($row->original_default_balance) ? $row->original_default_balance : '0000000';

                        $original_default_balance = '<input type="text" name="original_default_balance[' . $row->loan_info_id . ']" class="original_default_balance_' . $row->loan_info_id . ' w-100px update-value-cais text-center" data-name="original_default_balance" value="' . $original_default_balance_value . '" />';

                        return $original_default_balance;
                    })
                    ->editColumn('new_account_number', function ($row) {

                        $account_number_value = isset($row->new_account_number) && !empty($row->new_account_number) ? $row->new_account_number : '';

                        $new_account_number = '<input type="text" name="new_account_number[' . $row->loan_info_id . ']" class="new_account_number_' . $row->loan_info_id . ' w-100px update-value-cais text-center" data-name="new_account_number" value="' . $account_number_value . '" />';

                        return $new_account_number;
                    })
                    ->editColumn('new_person_number', function ($row) {

                        $person_number_value = isset($row->new_person_number) && !empty($row->new_person_number) ? $row->new_person_number : '';

                        $new_person_number = '<input type="text" name="new_person_number[' . $row->loan_info_id . ']" class="new_person_number_' . $row->loan_info_id . ' w-100px update-value-cais text-center" value="' . $person_number_value . '" data-name="new_person_number" />';

                        return $new_person_number;
                    })
                    ->rawColumns(['index_data', 'business_name', 'loan_number', 'name_change', 'previous_name_and_address', 'special_instruction_indicator', 'flag_settings', 'mortgage_flags', 'transfered_to_collection_account', 'default_satisfaction_date', 'original_default_balance', 'new_account_number', 'new_person_number'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("wp_business_info.business_name like ?", ["%{$keyword}%"])->orWhereRaw("wp_business_info.registration_number like ?", ["%{$keyword}%"])->orWhereRaw("wp_business_info.ProjectRef like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        $query->orderBy('wp_business_info.business_name', 'asc');
                    })
                    ->make(true);
        }

        $filters = [
            'get-creditsafe-details' => 'Update Credit Safe Details',
            'export-csv' => 'Export CSV File of CAIS',
            'export-txt' => 'Export CAIS - Equifax',
            'export-txt-experian' => 'Export CAIS - Experian',
            'save-data-only' => 'Save Data',
            'bulk-delete' => 'Delete',
        ];

        return view('cais.list')->with(compact('filters'));
    }

    public function add(Request $request)
    {
        $validations = [
            'add_reg_number' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }
    }

    public function updateValues(Request $request)
    {
        $validations = [
            'id' => 'required',
            'field' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $business_info = BusinessInfo::where('id', $request->id)->first();

        if (empty($business_info))
        {
            return response()->json(['status' => -1]);
        }

        if (in_array($request->field, ['special_instruction_indicator', 'flag_settings', 'mortgage_flags', 'transfered_to_collection_account', 'default_satisfaction_date', 'original_default_balance']))
        {
            LoanInfo::where('user_id', $business_info->user_id)->update([$request->field => $request->value]);
        }
        else
        {
            BusinessInfo::where('id', $request->id)->update([$request->field => $request->value]);
        }

        return response()->json(['status' => 1]);
    }

    public function action(Request $request)
    {
        $validations = [
            'action' => 'required|in:get-credit-safe-details,export-csv,export-txt,export-txt-experian',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        if ($request->action == 'get-credit-safe-details')
        {
            $businesses = BusinessInfo::where('is_selected', 1)->whereNotNull('registration_number')->where('registration_number', '!=', '')->orderBy('id', 'asc')->get();

            if (!$businesses->isEmpty())
            {
                $ga_credit_safe_db_columns = Helper::getOption('ga_credit_safe_db_columns');

                if (!empty($ga_credit_safe_db_columns))
                {
                    $ga_credit_safe_db_columns = array_keys($ga_credit_safe_db_columns);
                }

                foreach ($businesses as $key => $business)
                {
                    $credit_safe_id = null;

                    $details = Helper::getResultsOfCreditSafe($business->registration_number);

                    if (!empty($details) && isset($details['company_details']) && !empty($details['company_details']))
                    {
                        $credit_safe_id = Helper::updateCreditSafeCompanyData($business->registration_number, $details['company_details'], $ga_credit_safe_db_columns);
                    }

                    $business->ga_credit_safe_id = $credit_safe_id;
                    $business->save();
                }
            }

            return response()->json(['status' => 1, 'swal_title' => 'Information Updated', 'swal_message' => 'Selected bsuinesses data have been updated from credit safe.']);
        }

        return response()->json(['status' => 1]);
    }
}
