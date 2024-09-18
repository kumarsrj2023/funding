<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OpenBankingTransactions;
use DataTables;
use Storage;
use \Carbon\Carbon;
use DB;
use Excel;
use Helper;

class OpenBankingTransactionsController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax())
        {
            $data = DB::table('ga_plaid_request_log')->where('id', '!=', 0);
                
            return DataTables::of($data)
                    ->addColumn('index_data', function ($row) {

                        return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-'. $row->id .'" value="'. $row->id .'">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                </div>';
                        
                    })
                    ->editColumn('created_at', function ($row) {

                        return $row->created_at ? Carbon::parse($row->created_at)->format('d/m/Y h:i A') : '-';
                        
                    })
                    ->addColumn('actions', function ($row) {

                        return '<button type="button" class="btn btn-sm btn-danger me-1 remove-item-button" data-id="'. $row->id .'"><i class="ri-delete-bin-5-line"></i></button>';
                        
                    })
                    ->rawColumns(['index_data', 'created_at', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("email like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        $query->orderBy('id', 'desc');
                    })
                    ->make(true);
        }

        return view('open-banking-transactions.list');
    }

    public function add(Request $request)
    {
        $validations = [
            'email' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $config = Helper::plaidConfigInfo();

        $url = $config['url'] . '/link/token/create';
        $config['config']["transactions"]['days_requested'] = 730;
        $config['config']["redirect_uri"] = Helper::$site_home_url . "open-banking-transactions/";

        $data_string  = json_encode($config['config']);
        $auth = true;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        if (isset($result['link_token']))
        {
            $data = new OpenBankingTransactions();

            $data->email = $request->email;
            $data->link_token = $result['link_token'];
            $data->request_id = $result['request_id'];
            $data->step_number = 1;
            $data->response = json_encode($result);

            $data->save();

            $to = $request->email;

            if ($auth == '') 
            {
                $redirect_url = Helper::$site_home_url . 'obanking-transactions/?link_token=' . $result['link_token'] . '&email_address=' . $request->email;
            } 
            else 
            {
                $redirect_url = Helper::$site_home_url . 'open-banking-transactions/?link_token=' . $result['request_id'] . '&email_address=' . $request->email;
            }

            Helper::sendEmail('emails.open-banking-transactions', ['redirect_url' => $redirect_url], $to, 'Funding Alternative', [], null, null);

            return response()->json(['status' => 1, 'swal_message' => 'An Email has been sent to the ' . $request->email . ' with transaction link']);

        }
        else
        {
            return response()->json(['status' => -1, 'swal_title' => 'error', 'swal_error_message' => $result['error_message']]);
        }
    }

    public function remove(Request $request)
    {
        if (empty($request->id))
        {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids))
        {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        OpenBankingTransactions::whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }
}
