<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrokerList;
use App\Exports\Brokers as ExportBrokers;
use App\Imports\Brokers as ImportBrokers;
use DataTables;
use Storage;
use \Carbon\Carbon;
use DB;
use Excel;
use Helper;

class BrokerController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax())
        {
            $data = DB::table('wp_introducers_info')->where('id', '!=', 0);

            return DataTables::of($data)
                    ->addColumn('index_data', function ($row) {

                        return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-'. $row->id .'" value="'. $row->id .'">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                </div>';
                        
                    })
                    ->addColumn('row_url', function ($row) {

                        return route('broker.list.update', $row->id);
                        
                    })
                    ->editColumn('broker_email', function ($row) {

                        if (empty($row->broker_email))
                        {
                            return '-';
                        }

                        return '<a href="mailto:'. $row->broker_email .'" class="active-link-color">'. $row->broker_email .'</a>';
                        
                    })
                    ->editColumn('phone_no', function ($row) {

                        if (empty($row->phone_no))
                        {
                            return '-';
                        }

                        return '<a href="tel:'. $row->phone_no .'" class="active-link-color">'. $row->phone_no .'</a>';
                        
                    })
                    ->editColumn('region', function ($row) {

                        if (empty($row->region) || strtolower($row->region) == 'n/a')
                        {
                            return '-';
                        }

                        return $row->region;
                        
                    })
                    ->editColumn('business_name', function ($row) {

                        if (empty($row->business_name) || strtolower($row->business_name) == 'n/a')
                        {
                            return '-';
                        }

                        return $row->business_name;
                        
                    })
                    ->editColumn('introducer', function ($row) {

                        if (empty($row->introducer) || strtolower($row->introducer) == 'n/a')
                        {
                            return '-';
                        }

                        return $row->introducer;
                        
                    })
                    ->editColumn('broker_note', function ($row) {

                        if (empty($row->broker_note) || strtolower($row->broker_note) == 'n/a')
                        {
                            return '-';
                        }

                        return $row->broker_note;
                        
                    })
                    ->addColumn('actions', function ($row) {

                        return '<div class="action-and-check">
                                    <div class="form-check form-checkbox-dark d-inline-block">
                                        <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-'. $row->id .'" value="'. $row->id .'">
                                        <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                    </div>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-'. $row->id .'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-'. $row->id .'">
                                            <a class="dropdown-item" href="'. route('broker.list.update', $row->id) .'">Edit</a>
                                            <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                        </div>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'broker_email', 'phone_no', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->orWhereRaw("broker_name like ?", ["%{$keyword}%"])
                                ->orWhereRaw("broker_email like ?", ["%{$keyword}%"])
                                ->orWhereRaw("business_name like ?", ["%{$keyword}%"])
                                ->orWhereRaw("introducer like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        if (!empty(request()->sort_by))
                        {
                            $sort_data = explode('-', request()->sort_by);

                            if (isset($sort_data[0]) && !empty($sort_data[0]) && isset($sort_data[1]) && !empty($sort_data[1]) && in_array(strtolower($sort_data[1]), ['asc', 'desc']) && in_array(strtolower($sort_data[0]), ['broker_name', 'business_name', 'introducer']))
                            {
                                $query->orderBy(strtolower($sort_data[0]), strtolower($sort_data[1]));
                            }
                            else
                            {
                                $query->orderBy('broker_name', 'asc');
                            }
                        }
                        else
                        {
                            $query->orderBy('broker_name', 'asc');
                        }
                    })
                    ->make(true);
        }

        return view('brokers.list');
    }

    public function add(Request $request, $id = null)
    {
        $data = null;

        if (!empty($id))
        {
            $data = BrokerList::where('id', $id)->first();
        }

        if (request()->method() == 'GET')
        {
            return view('brokers.add')->with(compact('data'));
        }

        $validations = [
            'broker_name' => 'required|max:255',
            'broker_email' => 'required|email|max:50',
            'region' => 'required|max:255',
            'phone_no' => 'required|numeric|digits_between:1,15',
            'business_name' => 'required|max:255',
            'introducer' => 'required|max:255',
            'broker_note' => 'required|max:255',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $not_created_yet = false;

        if (empty($data))
        {
            $not_created_yet = true;
            $data = new BrokerList();
        }

        $data->broker_name = $request->broker_name;
        $data->broker_email = $request->broker_email;
        $data->region = $request->region;
        $data->phone_no = $request->phone_no;
        $data->business_name = $request->business_name;
        $data->introducer = $request->introducer;
        $data->broker_note = $request->broker_note;

        $data->save();

        if ($not_created_yet)
        {
            return redirect()->route('broker.list.update', $data->id)->with('message', 'New broker created as ' . $data->broker_name);
        }

        return redirect()->route('broker.list.update', $data->id)->with('message', 'Broker ' . $data->broker_name . ' updated');
    }

    public function export(Request $request)
    {
        $validations = [
            'type' => 'required|in:csv,xlsx',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $broker = BrokerList::where('id', '!=', 0)->first();

        if (empty($broker))
        {
            return response()->json(['status' => -1, 'error_message' => 'No broker found to export']);
        }

        return response()->json(['status' => 1, 'no_hide_modal' => 1, 'redirect_stop' => route('broker.list.export.file', $request->type)]);
    }

    public function exportFile(Request $request, $type = null)
    {
        if (empty($type) || !in_array(strtolower($type), ['csv', 'xlsx']))
        {
            return redirect()->route('broker.list');
        }

        $broker = BrokerList::where('id', '!=', 0)->first();

        if (empty($broker))
        {
            return redirect()->route('broker.list');
        }

        return Excel::download(new ExportBrokers, 'Brokers.' . strtolower($type));
    }

    public function import(Request $request)
    {
        $validations = [
            'file' => 'required|mimes:xlsx,csv',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        Excel::import(new ImportBrokers, $request->file('file'));

        return response()->json(['status' => 1, 'swal_message' => 'All the brokers have been uploaded', 'swal_title' => 'Uploaded!']);
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

        BrokerList::whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }
}
