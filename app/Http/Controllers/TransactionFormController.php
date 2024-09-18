<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionForm;
use DataTables;
use Storage;
use \Carbon\Carbon;
use DB;
use Excel;
use Helper;

class TransactionFormController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax())
        {
            $transaction_ids = DB::table('ga_transaction_form')->whereNotNull('tbl_transaction_id')->pluck('tbl_transaction_id')->toArray();

            $data = DB::table('tblFundingAltAccs')
                ->join('tblTransactionTable', 'tblFundingAltAccs.tbl_account_id', '=', 'tblTransactionTable.tbl_account_id');

            if (!empty($transaction_ids))
            {
                $data = $data->whereNotIn('tblTransactionTable.id', $transaction_ids);
            }

            $data = $data->select('tblFundingAltAccs.account_name', 'tblTransactionTable.Date', 'tblTransactionTable.name as Transaction', 'tblTransactionTable.id', 'tblTransactionTable.amount');

            return DataTables::of($data)
                    ->addColumn('index_data', function ($row) {

                        return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-'. $row->id .'" value="'. $row->id .'">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                </div>';
                        
                    })
                    ->addColumn('amount', function ($row) {

                        if (empty($row->amount))
                        {
                            return '-';
                        }

                        return Helper::displayPrice($row->amount);
                        
                    })
                    ->rawColumns(['index_data'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->orWhereRaw("account_name like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        $query->orderBy('tblTransactionTable.Date', 'desc')->orderBy('tblTransactionTable.name', 'asc');
                    })
                    ->make(true);
        }

        return view('transaction-form.list');
    }

    public function add(Request $request)
    {
        $validations = [
            'category' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $data = new TransactionForm();

        $deal_id = $transaction = null;

        if (!empty($request->transaction_id))
        {
            $transaction = DB::table('tblTransactionTable')->where('id', $request->transaction_id)->first();
        }

        $data->amount = !empty($transaction) ? ($transaction->amount * -1) : null;
        $data->transaction_id = !empty($request->transaction_id) ? $request->transaction_id : null;
        $data->debtor_id = $request->debtor_id;

        if (isset($request->business_unit) && !empty($request->business_unit)) 
        {
            $data->business_unit = $request->business_unit;

            if (strtoupper($request->business_unit) == 'BCA') 
            {
                $deal_id = 'FCA' . $request->debtor_id;

                if (!empty($request->transaction_id))
                {
                    $deal_id .= '-' . $request->transaction_id;
                }
                
                $data->transaction_id = null;
            }
            elseif (strtoupper($request->business_unit) == 'SIF') 
            {
                $deal_id = 'FYI' . $request->debtor_id . '-' . $request->transaction_id;

                if (!empty($request->transaction_id))
                {
                    $deal_id .= '-' . $request->transaction_id;
                }
            }
            elseif (strtoupper($request->business_unit) == 'OPS') 
            {
                $data->transaction_id = null;
                $data->debtor_id = null;
            }
        }
        
        $data->deal_id = $deal_id;
        $data->category = !empty($request->category) ? $request->category : null;
        $data->comments = !empty($request->comments) ? $request->comments : null;
        $data->tbl_transaction_id = !empty($transaction) ? $transaction->id : null;

        $data->save();

        return response()->json(['status' => 1]);
    }
}
