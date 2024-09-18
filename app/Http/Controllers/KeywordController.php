<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Exports\Keywords as ExportKeywords;
use App\Imports\Keywords as ImportKeywords;
use DataTables;
use Storage;
use \Carbon\Carbon;
use DB;
use Excel;
use Helper;

class KeywordController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax())
        {
            $data = DB::table('KeyWords')->where('id', '!=', 0);

            if (!empty($request->project_ref))
            {
                $data = $data->whereRaw("DealSpecific like ?", ["%". $request->project_ref ."%"]);
            }

            if (!empty($request->description))
            {
                $data = $data->where('Description', $request->description);
            }

            return DataTables::of($data)
                    ->addColumn('index_data', function ($row) {

                        return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-'. $row->id .'" value="'. $row->id .'">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                </div>';
                        
                    })
                    ->editColumn('SearchTerm1', function ($row) {

                        return !empty($row->SearchTerm1) ? $row->SearchTerm1 : '-';
                        
                    })
                    ->editColumn('SearchTerm2', function ($row) {

                        return !empty($row->SearchTerm2) ? $row->SearchTerm2 : '-';
                        
                    })
                    ->editColumn('Amount', function ($row) {

                        $amount = Helper::displayPrice($row->Amount);
                        return !empty($amount) ? $amount : '-';
                        
                    })
                    ->editColumn('Excluding', function ($row) {

                        return !empty($row->Excluding) ? $row->Excluding : '-';
                        
                    })
                    ->editColumn('Description', function ($row) {

                        return !empty($row->Description) ? $row->Description : '-';
                        
                    })
                    ->editColumn('Description2', function ($row) {

                        return !empty($row->Description2) ? $row->Description2 : '-';
                        
                    })
                    ->editColumn('DealSpecific', function ($row) {

                        return !empty($row->DealSpecific) ? $row->DealSpecific : '-';
                        
                    })
                    ->addColumn('row_url', function ($row) {

                        return route('keywords.update', $row->id);
                        
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
                                            <a class="dropdown-item" href="'. route('keywords.update', $row->id) .'">Edit</a>
                                            <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                        </div>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'SearchTerm1', 'SearchTerm2', 'Amount', 'Description', 'Description2', 'DealSpecific', 'Excluding', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->orWhereRaw("SearchTerm1 like ?", ["%{$keyword}%"])
                                ->orWhereRaw("SearchTerm2 like ?", ["%{$keyword}%"])
                                ->orWhereRaw("Description like ?", ["%{$keyword}%"])
                                ->orWhereRaw("Description2 like ?", ["%{$keyword}%"])
                                ->orWhereRaw("DealSpecific like ?", ["%{$keyword}%"])
                                ->orWhereRaw("Excluding like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        if (!empty(request()->sort_by))
                        {
                            $sort_data = explode('-', request()->sort_by);

                            if (isset($sort_data[0]) && !empty($sort_data[0]) && isset($sort_data[1]) && !empty($sort_data[1]) && in_array(strtolower($sort_data[1]), ['asc', 'desc']) && in_array(strtolower($sort_data[0]), ['SearchTerm1', 'SearchTerm2', 'Excluding']))
                            {
                                $query->orderBy(strtolower($sort_data[0]), strtolower($sort_data[1]));
                            }
                            else
                            {
                                $query->orderBy('SearchTerm1', 'asc');
                            }
                        }
                        else
                        {
                            $query->orderBy('SearchTerm1', 'asc');
                        }
                    })
                    ->make(true);
        }

        $bank_statements = DB::table('tblDD_BankStatements')->whereNotNull('ProjectRef')->groupBy('ProjectRef')->orderBy('ProjectRef', 'asc')->get();
        $keywords_sort_order = DB::table('KeyWords_SortOrder')->whereNotNull('Field')->groupBy('Field')->orderBy('Field', 'asc')->get();

        return view('keywords.list')->with(compact('bank_statements', 'keywords_sort_order'));
    }

    public function add(Request $request, $id = null)
    {
        $data = null;

        if (!empty($id))
        {
            $data = Keyword::where('id', $id)->first();
        }

        if (request()->method() == 'GET')
        {
            $bank_statements = DB::table('tblDD_BankStatements')->whereNotNull('ProjectRef')->groupBy('ProjectRef')->orderBy('ProjectRef', 'asc')->get();
            $keywords_sort_order = DB::table('KeyWords_SortOrder')->whereNotNull('Field')->groupBy('Field')->orderBy('Field', 'asc')->get();

            return view('keywords.add')->with(compact('data', 'bank_statements', 'keywords_sort_order'));
        }

        $validations = [
            'SearchTerm1' => 'required|max:86',
            'SearchTerm2' => 'max:18',
            'Excluding' => 'max:18',
            'Amount' => 'max:2',
            'Description' => 'required|max:27',
            'Description2' => 'max:40',
            'DealSpecific' => 'required|max:17',
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
            $data = new keyword();
        }

        $data->SearchTerm1 = $request->SearchTerm1;
        $data->SearchTerm2 = $request->SearchTerm2;
        $data->Excluding = $request->Excluding;
        $data->Amount = $request->Amount;
        $data->Description = $request->Description;
        $data->Description2 = $request->Description2;
        $data->DealSpecific = $request->DealSpecific;

        $data->save();

        if ($not_created_yet)
        {
            return redirect()->route('keywords.update', $data->id)->with('message', 'New keyword created as ' . $data->SearchTerm1);
        }

        return redirect()->route('keywords.update', $data->id)->with('message', 'Keyword ' . $data->SearchTerm1 . ' updated');
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

        $keyword = Keyword::where('id', '!=', 0)->first();

        if (empty($keyword))
        {
            return response()->json(['status' => -1, 'error_message' => 'No keyword found to export']);
        }

        return response()->json(['status' => 1, 'no_hide_modal' => 1, 'redirect_stop' => route('keywords.export.file', $request->type)]);
    }

    public function exportFile(Request $request, $type = null)
    {
        if (empty($type) || !in_array(strtolower($type), ['csv', 'xlsx']))
        {
            return redirect()->route('keywords');
        }

        $keyword = Keyword::where('id', '!=', 0)->first();

        if (empty($keyword))
        {
            return redirect()->route('keywords');
        }

        return Excel::download(new ExportKeywords, 'Keywords.' . strtolower($type));
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

        Excel::import(new ImportKeywords, $request->file('file'));

        return response()->json(['status' => 1, 'swal_message' => 'All the keywords have been uploaded', 'swal_title' => 'Uploaded!']);
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

        Keyword::whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }
}
