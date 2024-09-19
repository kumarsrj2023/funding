<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerInfo;
use App\Models\BusinessInfo;
use App\Models\DirectorInfo;
use App\Models\LoanInfo;
use App\Models\CommitteeMeeting;
use App\Models\CardPayment;
use App\Models\OpenBankingPayments;
use App\Exports\Directors as ExportDirectors;
use DataTables;
use Storage;
use \Carbon\Carbon;
use DB;
use Excel;
use Helper;

use App\Models\DirectorAsset;
use App\Models\DirectorLiabilities;
use App\Mail\SOPNotification;
use App\Models\DirectorContingentLiabilities;
use App\Models\DirectorHouseholdIncome;
use App\Models\DirectorPropertiesAndOtherAssets;
use App\Models\DirectorUnlistedShares;
use Mpdf\Mpdf;


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('wp_business_info')->where('id', '!=', 0);

            return DataTables::of($data)
                ->addColumn('index_data', function ($row) {

                    return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                </div>';
                })
                ->editColumn('business_name', function ($row) {

                    return $row->business_name;
                })
                ->addColumn('row_url', function ($row) {

                    return route('businesses.customer.info', $row->id);
                })
                ->addColumn('actions', function ($row) {

                    return '<div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-' . $row->id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-' . $row->id . '">
                                        <a class="dropdown-item" href="' . route('businesses.customer.info', $row->id) . '">Details</a>
                                        <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="' . $row->id . '">Remove</a>
                                    </div>
                                </div>';
                })
                ->rawColumns(['index_data', 'business_name', 'actions'])
                ->filterColumn('filter_index', function ($query, $keyword) {
                    $query->whereRaw("business_name like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('filter_index', function ($query, $keyword) {
                    $query->whereRaw("business_name like ?", ["%{$keyword}%"])
                        ->orWhereRaw("ProjectRef like ?", ["%{$keyword}%"])
                        ->orWhereRaw("registration_number like ?", ["%{$keyword}%"]);
                })
                ->order(function ($query) {

                    if (!empty(request()->sort_by)) {
                        $sort_data = explode('-', request()->sort_by);

                        if (isset($sort_data[0]) && !empty($sort_data[0]) && isset($sort_data[1]) && !empty($sort_data[1]) && in_array(strtolower($sort_data[1]), ['asc', 'desc']) && in_array(strtolower($sort_data[0]), ['business_name', 'created_at'])) {
                            $query->orderBy(strtolower($sort_data[0]), strtolower($sort_data[1]));
                        } else {
                            $query->orderBy('id', 'desc');
                        }
                    } else {
                        $query->orderBy('id', 'desc');
                    }
                })
                ->make(true);
        }

        return view('businesses.list');
    }

    public function customerInfo(Request $request, $id = null)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        $data = $not_created_yet = null;

        $business_info = BusinessInfo::where('id', $id)->first();

        if (!empty($business_info)) {
            $data = CustomerInfo::where('user_id', $business_info->user_id)->first();
        }

        if (request()->method() == 'GET') {
            return view('businesses.add.customer-info')->with(compact('data', 'id', 'business_info'));
        }
    }

    public function businessInfo(Request $request, $id = null)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $data = BusinessInfo::where('id', $id)->first();

        if (empty($data)) {
            return redirect()->route('home');
        }

        if (request()->method() == 'GET') {
            return view('businesses.add.business-info')->with(compact('data', 'id'));
        }

        $validations = [
            'business_name' => 'required',
            'registration_number' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data->business_name = $request->business_name;
        $data->industry = $request->industry;
        $data->registration_number = $request->registration_number;
        $data->unique_tax_reference_number = $request->unique_tax_reference_number;
        $data->date_of_incorporation = $request->date_of_incorporation;
        $data->ProjectRef = $request->ProjectRef;
        $data->DealDate = $request->DealDate;
        $data->time_in_business = $request->time_in_business;
        $data->monthly_turnover = $request->monthly_turnover;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->postal_code = $request->postal_code;
        $data->country = $request->country;
        $data->checked_address = isset($request->checked_address) && !empty($request->checked_address) ? true : false;

        if (isset($request->checked_address) && !empty($request->checked_address)) {
            if (strtolower($request->checked_address) == 'on') {
                $data->traddress = $request->address;
                $data->trcity = $request->city;
                $data->trstate = $request->state;
                $data->trpostal_code = $request->postal_code;
                $data->trcountry = $request->country;
            } else {
                $data->traddress = $request->traddress;
                $data->trcity = $request->trcity;
                $data->trstate = $request->trstate;
                $data->trpostal_code = $request->trpostal_code;
                $data->trcountry = $request->trcountry;
            }
        }

        $data->years_at_address = $request->years_at_address;
        $data->website_address = $request->website_address;
        $data->monitored_company_email = $request->monitored_company_email;
        $data->switchboard_number = $request->switchboard_number;
        $data->reason_for_funding = $request->reason_for_funding;
        $data->future_business_plan = $request->future_business_plan;

        if (isset($request->forecast) && !empty($request->file('forecast'))) {
            $image_file_path = [];
            $all_file_path = [];

            foreach ($request->file('forecast') as $file) {
                if ($file->isValid()) {
                    $mime_type = $file->getMimeType();

                    $path = $file->store('public/files/forecast');
                    $file_path = str_replace('public/', '', $path);

                    if (str_contains($mime_type, 'image')) {
                        $image_file_path[] = $file_path;
                    } else {
                        $all_file_path[] = $file_path;
                    }
                }
            }

            $data->forecast_img_type = !empty($image_file_path) ? json_encode($image_file_path) : null;

            $data->forecast_all_type = !empty($all_file_path) ? json_encode($all_file_path) : null;
        }

        $data->director_1_title = $request->director_1_title;
        $data->director_1_forename = $request->director_1_forename;
        $data->director_1_middlename = $request->director_1_middlename;
        $data->director_1_surname = $request->director_1_surname;
        $data->director_1_dob = $request->director_1_dob;
        $data->director_1_housenumber = $request->director_1_housenumber;
        $data->director_1_housename = $request->director_1_housename;
        $data->director_1_postcode = $request->director_1_postcode;
        $data->director_1_years = $request->director_1_years;
        $data->director_1_months = $request->director_1_months;
        $data->director_2_title = $request->director_2_title;
        $data->director_2_forename = $request->director_2_forename;
        $data->director_2_middlename = $request->director_2_middlename;
        $data->director_2_surname = $request->director_2_surname;
        $data->director_2_dob = $request->director_2_dob;
        $data->director_2_housenumber = $request->director_2_housenumber;
        $data->director_2_housename = $request->director_2_housename;
        $data->director_2_postcode = $request->director_2_postcode;
        $data->director_2_years = $request->director_2_years;
        $data->director_2_months = $request->director_2_months;
        $data->director_3_title = $request->director_3_title;
        $data->director_3_forename = $request->director_3_forename;
        $data->director_3_middlename = $request->director_3_middlename;
        $data->director_3_surname = $request->director_3_surname;
        $data->director_3_dob = $request->director_3_dob;
        $data->director_3_housenumber = $request->director_3_housenumber;
        $data->director_3_housename = $request->director_3_housename;
        $data->director_3_postcode = $request->director_3_postcode;
        $data->director_3_years = $request->director_3_years;
        $data->director_3_months = $request->director_3_months;


        if (isset($request->inc_stmt) && !empty($request->file('inc_stmt'))) {
            $image_file_path = [];
            $all_file_path = [];

            foreach ($request->file('inc_stmt') as $file) {
                if ($file->isValid()) {
                    $mime_type = $file->getMimeType();

                    $path = $file->store('public/files/income_statement');
                    $file_path = str_replace('public/', '', $path);

                    if (str_contains($mime_type, 'image')) {
                        $image_file_path[] = $file_path;
                    } else {
                        $all_file_path[] = $file_path;
                    }
                }
            }

            $data->inc_stmt_img_type = !empty($image_file_path) ? json_encode($image_file_path) : null;

            $data->inc_stmt_all_type = !empty($all_file_path) ? json_encode($all_file_path) : null;
        }

        //Balance_sheet                
        $data->assets = $request->assets;
        $data->current_assets = $request->current_assets;
        $data->fixed_assets = $request->fixed_assets;
        $data->other_assets = $request->other_assets;
        $data->total_assets = $request->total_assets;
        $data->liabilities = $request->liabilities;
        $data->current_liabilities = $request->current_liabilities;
        $data->long_term_liabilities = $request->long_term_liabilities;
        $data->other_liabilities = $request->other_liabilities;
        $data->total_liabilities = $request->total_liabilities;
        $data->stockholder_equity = $request->stockholder_equity;
        $data->total_equity = $request->total_equity;

        //Bank_account
        $data->name_of_bank = $request->name_of_bank;
        $data->branch_name = $request->branch_name;
        $data->branch_number = $request->branch_number;
        $data->account_type = $request->account_type;
        $data->account_number = $request->account_number;

        if (isset($request->bank_detail) && !empty($request->file('bank_detail'))) {

            $image_file_path = [];
            $all_file_path = [];

            foreach ($request->file('bank_detail') as $file) {
                if ($file->isValid()) {
                    $mime_type = $file->getMimeType();

                    $path = $file->store('public/files/bank_detail');
                    $file_path = str_replace('public/', '', $path);

                    if (str_contains($mime_type, 'image')) {
                        $image_file_path[] = $file_path;
                    } else {
                        $all_file_path[] = $file_path;
                    }
                }
            }

            $data->bank_detail_img_type = !empty($image_file_path) ? json_encode($image_file_path) : null;

            $data->bank_detail_all_type = !empty($all_file_path) ? json_encode($all_file_path) : null;
        }

        $data->save();

        return redirect()->route('businesses.business.info', $id)->with('message', 'Business ' . $data->business_name . ' updated');
    }

    public function directorInfo(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        if ($request->ajax()) {
            $data = DB::table('wp_director_info')->where('wp_business_info_id', $id);

            return DataTables::of($data)
                ->addColumn('index_data', function ($row) {

                    return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                </div>';
                })
                ->editColumn('address_simple_value', function ($row) {

                    $address = '';

                    $address_simple_value = isset($row->address_simple_value) && !empty($row->address_simple_value) ? $row->address_simple_value : '';
                    $address_street = isset($row->address_street) && !empty($row->address_street) ? ' , ' . $row->address_street : '';
                    $address_city = isset($row->address_city) && !empty($row->address_city) ? ' , ' . $row->address_city : '';
                    $address_postal_code = isset($row->address_postal_code) && !empty($row->address_postal_code) ? ' , ' . $row->address_postal_code : '';

                    $address = $address_simple_value . $address_street . $address_city . $address_postal_code;

                    return !empty($address) ? $address : '-';
                })
                ->editColumn('date_of_birth', function ($row) {

                    $date_of_birth = isset($row->date_of_birth) && !empty($row->date_of_birth) ? date("d-m-Y", strtotime($row->date_of_birth)) : '-';

                    return $date_of_birth;
                })
                ->editColumn('date_appointed', function ($row) {

                    $date_appointed = isset($row->date_appointed) && !empty($row->date_appointed) ? date("d-m-Y", strtotime($row->date_appointed)) : '-';

                    return $date_appointed;
                })
                ->editColumn('name', function ($row) {

                    return !empty($row->name) ? $row->name : '-';
                })
                ->editColumn('gender', function ($row) {

                    return !empty($row->gender) ? $row->gender : '-';
                })
                ->editColumn('nationality', function ($row) {

                    return !empty($row->nationality) ? $row->nationality : '-';
                })
                ->editColumn('occupation', function ($row) {

                    return !empty($row->occupation) ? $row->occupation : '-';
                })
                ->addColumn('row_url', function ($row) use ($id) {

                    return route('businesses.director.info.add', [$id, $row->id]);
                })
                ->addColumn('actions', function ($row) use ($id) {

                    return '<div class="action-and-check">
                                    <div class="form-check form-checkbox-dark d-inline-block">
                                        <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                        <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                    </div>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-' . $row->id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-' . $row->id . '">
                                            <a class="dropdown-item" href="' . route('businesses.director.info.add', [$id, $row->id]) . '">Edit</a>
                                            <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="' . $row->id . '">Remove</a>
                                        </div>
                                    </div>
                                </div>';
                })
                ->rawColumns(['index_data', 'address_simple_value', 'date_of_birth', 'date_appointed', 'actions'])
                ->filterColumn('filter_index', function ($query, $keyword) {
                    $query->orWhereRaw("name like ?", ["%{$keyword}%"])
                        ->orWhereRaw("nationality like ?", ["%{$keyword}%"])
                        ->orWhereRaw("occupation like ?", ["%{$keyword}%"]);
                })
                ->order(function ($query) {

                    $query->orderBy('id', 'asc');
                })
                ->make(true);
        }

        return view('businesses.add.director-info.list')->with(compact('id'));
    }

    public function directorInfoAdd(Request $request, $id, $d_id = null)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        $data = null;

        if (!empty($d_id)) {
            $data = DirectorInfo::where('id', $d_id)->first();
        }

        if (request()->method() == 'GET') {
            return view('businesses.add.director-info.add')->with(compact('data', 'id'));
        }

        $validations = [
            'first_name' => 'required',
            'surname' => 'required',
            'address_simple_value' => 'required',
            'phone' => 'max:15',
            'address_postal_code' => 'max:20',
            'gender' => 'max:50',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $not_created_yet = false;

        if (empty($data)) {
            $not_created_yet = true;
            $data = new DirectorInfo();
            $data->wp_business_info_id = $id;
        }

        $data->name = $request->title . ' ' . $request->first_name . ' ' . $request->surname;
        $data->title = $request->title;
        $data->first_name = $request->first_name;
        $data->phone = $request->first_name;
        $data->email = $request->email;
        $data->surname = $request->surname;
        $data->address_type = $request->address_type;
        $data->address_simple_value = $request->address_simple_value;
        $data->address_street = $request->address_street;
        $data->address_city = $request->address_city;
        $data->address_postal_code = $request->address_postal_code;
        $data->gender = $request->gender;
        $data->date_of_birth = $request->date_of_birth;
        $data->nationality = $request->nationality;
        $data->director_type = $request->director_type;
        $data->date_appointed = $request->date_appointed;
        $data->position_name = $request->position_name;
        $data->present_appointments = $request->present_appointments;
        $data->occupation = $request->occupation;
        $data->ni_number = $request->ni_number;

        $data->save();

        if ($not_created_yet) {
            return redirect()->route('businesses.director.info.add', [$id, $data->id])->with('message', 'New director created as ' . $data->first_name);
        }

        return redirect()->route('businesses.director.info.add', [$id, $data->id])->with('message', 'Director ' . $data->first_name . ' updated');
    }

    public function directorExport(Request $request, $id)
    {
        $validations = [
            'type' => 'required|in:csv,xlsx',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $director = DirectorInfo::where('id', $id)->first();

        if (empty($director)) {
            return response()->json(['status' => -1, 'error_message' => 'No director found to export']);
        }

        return response()->json(['status' => 1, 'no_hide_modal' => 1, 'redirect_stop' => route('businesses.director.info.export.file', [$request->type, $id])]);
    }

    public function directorExportFile(Request $request, $type = null, $id)
    {
        if (empty($type) || !in_array(strtolower($type), ['csv', 'xlsx'])) {
            return redirect()->route('businesses.director.info', $id);
        }

        $director = DirectorInfo::where('id', $id)->first();

        if (empty($director)) {
            return redirect()->route('businesses.director.info', $id);
        }

        return Excel::download(new ExportDirectors($id), 'Directors.' . strtolower($type));
    }

    public function directorInfoRemove(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        DirectorInfo::whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }

    // by suraj
    public function directorInfoDataforSOP(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        if ($request->ajax()) {
            $data = DB::table('wp_director_info')->where('wp_business_info_id', $id);

            return DataTables::of($data)
                ->addColumn('index_data', function ($row) {
                    return '<div class="form-check form-checkbox-dark">
                                <input type="checkbox" class="form-check-input select-sop-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                            </div>';
                })
                ->editColumn('address_simple_value', function ($row) {
                    $address = '';

                    $address_simple_value = isset($row->address_simple_value) && !empty($row->address_simple_value) ? $row->address_simple_value : '';
                    $address_street = isset($row->address_street) && !empty($row->address_street) ? ' , ' . $row->address_street : '';
                    $address_city = isset($row->address_city) && !empty($row->address_city) ? ' , ' . $row->address_city : '';
                    $address_postal_code = isset($row->address_postal_code) && !empty($row->address_postal_code) ? ' , ' . $row->address_postal_code : '';

                    $address = $address_simple_value . $address_street . $address_city . $address_postal_code;

                    return !empty($address) ? $address : '-';
                })
                ->editColumn('name', function ($row) {
                    return !empty($row->name) ? $row->name : '-';
                })
                ->editColumn('email', function ($row) {
                    return !empty($row->email) ? $row->email : '-';
                })
                ->addColumn('actions', function ($row) use ($id) {

                    return '<div class="action-and-check">
                                <div class="form-check form-checkbox-dark d-inline-block">
                                    <input type="checkbox" class="form-check-input select-sop-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                </div>
                            </div>';
                })
                ->rawColumns(['index_data', 'address_simple_value', 'actions'])
                ->make(true);
        }


        return view('businesses.add.director-info.list')->with(compact('id'));
    }

    // by suraj
    public function sendSOP(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        // Fetch director emails by ids
        $directorInfo = DB::table('wp_director_info')->whereIn('id', $ids)->get();
        $successfulEmails = [];
        $failedEmails = [];
        $subject = 'Generate SOP';

        foreach ($directorInfo as $director) {
            if ($director->email) {
                $encryptedId = Crypt::encrypt($director->id);
                try {
                    // Send email using Mail::send
                    Mail::send('emails.sop_notification', [
                        'data' => $director,
                        'encryptedId' => $encryptedId,
                        'subject' => $subject,
                    ], function ($message) use ($director, $subject) {
                        $message->to($director->email)
                            ->subject($subject);
                    });

                    $successfulEmails[$director->name] = [
                        'email' => $director->email,
                        'id' => $encryptedId,
                    ];
                } catch (\Exception $e) {
                    $failedEmails[] = $director->email . ' (Error: ' . $e->getMessage() . ')';
                }
            }
        }

        return response()->json([
            'status' => 1,
            'message' => count($successfulEmails) > 0 ? 'Emails sent successfully' : 'No emails were sent.',
            'successful_emails' => $successfulEmails,
            'failed_emails' => $failedEmails
        ]);
    }

    // by suraj
    public function sopForm(Request $request, $encryptedDirectorId = null)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($encryptedDirectorId)) {
            return redirect()->route('home');
        }

        // Declare all variables
        $decryptedId = null;

        try {
            $decryptedId = Crypt::decrypt($encryptedDirectorId);
        } catch (\Exception $e) {
            return abort(404, 'Invalid or tampered URL');
        }

        // Fetch All Data from the database
        $directorInfo = DirectorInfo::where('id', $decryptedId)->first();

        if ($request->isMethod('GET')) {
            $businessInfo = BusinessInfo::where('id', $directorInfo->wp_business_info_id)->first();
            $assetInfo = DirectorAsset::where('wp_director_info_id', $decryptedId)->first();
            $liabilitiesInfo = DirectorLiabilities::where('wp_director_info_id', $decryptedId)->first();
            $otherAssets = DirectorPropertiesAndOtherAssets::where('wp_director_info_id', $decryptedId)->get();
            $contingentLib = DirectorContingentLiabilities::where('wp_director_info_id', $decryptedId)->get();
            $householdIncome = DirectorHouseholdIncome::where('wp_director_info_id', $decryptedId)->get();
            $unlistedShares = DirectorUnlistedShares::where('wp_director_info_id', $decryptedId)->get();

            return view('sop.sop-form', ['decryptedId' => $decryptedId, 'directorInfo' => $directorInfo, 'businessInfo' => $businessInfo, 'assetInfo' => $assetInfo, 'liabilitiesInfo' => $liabilitiesInfo, 'otherAssets' => $otherAssets, 'contingentLib' => $contingentLib, 'householdIncome' => $householdIncome, 'unlistedShares' => $unlistedShares]);
        }

        if ($request->isMethod('POST')) {

            $rules = [
                'proposed_guarantor_details_title' => 'required|string|max:50',
            ];

            $attributes = [
                'proposed_guarantor_details_title' => 'Company Title',
            ];

            $validatedData = $request->validate($rules, [], $attributes);

            try {

                if (!empty($directorInfo)) {
                    $directorInfo->name = trim(
                        $request->proposed_guarantor_details_title . ' ' .
                            ($request->proposed_guarantor_details_middle_name ?? '') . ' ' .
                            $request->proposed_guarantor_details_surname
                    );

                    $directorInfo->name = trim($request->proposed_guarantor_details_title . ' ' . $request->proposed_guarantor_details_first_name . ' ' . $request->proposed_guarantor_details_middle_name . ' ' . $request->proposed_guarantor_details_surname);
                    $directorInfo->title = $request->proposed_guarantor_details_title;
                    $directorInfo->first_name = $request->proposed_guarantor_details_first_name;
                    $directorInfo->middle_name = $request->proposed_guarantor_details_middle_name;
                    $directorInfo->surname = $request->proposed_guarantor_details_surname;
                    $directorInfo->email = $request->proposed_guarantor_details_email;
                    $directorInfo->mobile = $request->proposed_guarantor_details_mobile;
                    $directorInfo->tel_home = $request->proposed_guarantor_details_tel_home;
                    $directorInfo->tel_business = $request->proposed_guarantor_details_tel_business;
                    $directorInfo->address_simple_value = $request->proposed_guarantor_details_address_simple_value;
                    $directorInfo->time_in_curr_address = $request->proposed_guarantor_details_time_in_curr_address;
                    $directorInfo->date_of_birth = $request->proposed_guarantor_details_date_of_birth;
                    $directorInfo->declared_bankrupt = $request->proposed_guarantor_details_declared_bankrupt;

                    $directorInfo->save();
                }

                $asset = DirectorAsset::updateOrCreate(
                    ['wp_director_info_id' => $decryptedId],

                    [
                        'account_or_regnumber' => $request->assets_account_or_regnumber ?? null,
                        'wp_director_info_id' => $decryptedId,
                        'cash_in_bank_and_deposit' => $request->assets_cash_in_bank_and_deposit ?? null,
                        'public_listed_shares' => $request->assets_public_listed_shares ?? null,
                        'properties' => (bool) $request->assets_properties ?? null,
                        'motor_vehicles_boats' => $request->assets_motor_vehicles_boats ?? null,
                        'other_cash_investments' => $request->assets_other_cash_investments ?? null,
                        'details_of_personal_pension' => $request->assets_details_of_personal_pension ?? null,
                        'other_assets' => $request->assets_other_assets ?? null,
                        'updated_at' => now()
                    ]
                );

                $liabilities = DirectorLiabilities::updateOrCreate(
                    ['wp_director_info_id' => $decryptedId],

                    [
                        'account_or_regnumber' => $request->liabilities_account_or_regnumber ?? null,
                        'wp_director_info_id' => $decryptedId,
                        'personal_loans_and_overdrafts' => $request->liabilities_personal_loans_and_overdrafts ?? null,
                        'mortgages' => $request->liabilities_mortgages ?? null,
                        'credit_card_debts' => $request->liabilities_credit_card_debts ?? null,
                        'motor_loan' => $request->liabilities_motor_loan ?? null,
                        'property_rental' => $request->liabilities_property_rental ?? null,
                        'other_debt_and_contingent_liabilities' => $request->liabilities_other_debt_and_contingent_liabilities ?? null,
                        'other_liabilities' => $request->liabilities_other_liabilities ?? null,
                        'updated_at' => now()
                    ]
                );


                // Retrieve the other assets data from the request
                $otherAssetsData = $request->input('otherAssets');
                DirectorPropertiesAndOtherAssets::where('wp_director_info_id', $decryptedId)->delete();
                DB::statement('ALTER TABLE wp_director_properties_and_other_assets AUTO_INCREMENT = 1');

                foreach ($otherAssetsData as $assetData) {
                    DirectorPropertiesAndOtherAssets::create([
                        'wp_director_info_id' => $decryptedId,
                        'property_address_and_assets' => $assetData['property_address_and_assets'],
                        'estimated_value' => $assetData['estimated_value'] ?? null,
                        'debt' => $assetData['debt'] ?? null,
                        'financing_costs' => $assetData['financing_costs'] ?? null,
                        'income' => $assetData['income'] ?? null,
                        'updated_at' => now()
                    ]);
                }

                // Retrieve the Contingent Liabilities data from the request
                $contingentLibData = $request->input('contingentLib');
                DirectorContingentLiabilities::where('wp_director_info_id', $decryptedId)->delete();
                DB::statement('ALTER TABLE wp_director_contingent_liabilities AUTO_INCREMENT = 1');

                foreach ($contingentLibData as $contingentData) {
                    DirectorContingentLiabilities::create([
                        'wp_director_info_id' => $decryptedId,
                        'creditor' => $contingentData['creditor'],
                        'nature_of_pg' => $contingentData['nature_of_pg'] ?? null,
                        'unlimited_guarantee_or_limit_value' => $contingentData['unlimited_guarantee_or_limit_value'] ?? null,
                        'updated_at' => now()
                    ]);
                }

                // Retrieve the Director Household Income data from the request
                $householdIncomeData = $request->input('householdIncome');
                DirectorHouseholdIncome::where('wp_director_info_id', $decryptedId)->delete();
                DB::statement('ALTER TABLE wp_director_household_income AUTO_INCREMENT = 1');

                foreach ($householdIncomeData as $householdIncome) {
                    DirectorHouseholdIncome::create([
                        'wp_director_info_id' => $decryptedId,
                        'type_and_source' => $householdIncome['type_and_source'],
                        'who_in_household' => $householdIncome['who_in_household'] ?? null,
                        'gross_annual_income' => $householdIncome['gross_annual_income'] ?? null,
                        'updated_at' => now()
                    ]);
                }

                // Retrieve the Director Household Income data from the request
                $unlistedSharesData = $request->input('unlistedShares');
                DirectorUnlistedShares::where('wp_director_info_id', $decryptedId)->delete();
                DB::statement('ALTER TABLE wp_director_unlisted_shares AUTO_INCREMENT = 1');

                foreach ($unlistedSharesData as $unlistedShare) {
                    DirectorUnlistedShares::create([
                        'wp_director_info_id' => $decryptedId,
                        'company_name' => $unlistedShare['company_name'],
                        'reg_number' => $unlistedShare['reg_number'] ?? null,
                        'status' => $unlistedShare['status'] ?? null,
                        'registered' => $unlistedShare['registered'] ?? null,
                        'shareholding' => $unlistedShare['shareholding'] ?? null,
                        'updated_at' => now()
                    ]);
                }


                $signatureInput = $request->input('signature');
                if (preg_match('/^data:image\/(\w+);base64,/', $signatureInput, $type)) {
                    $imageType = strtolower($type[1]);
                    $imageType = 'jpg';

                    $base64Data = substr($signatureInput, strpos($signatureInput, ',') + 1);
                    $decodedImage = base64_decode($base64Data);

                    $signaturesPath = public_path('signatures');
                    if (!file_exists($signaturesPath)) {
                        mkdir($signaturesPath, 0777, true);
                    }

                    $fileName = 'signature_' . $decryptedId . '.' . $imageType;
                    $filePath = $signaturesPath . '/' . $fileName;

                    file_put_contents($filePath, $decodedImage);

                    DB::table('wp_director_info')
                        ->where('id', $decryptedId)
                        ->update(['signature' => $fileName]);
                }


                // Download SOP Pdf
                if ($request->input('download_pdf')) {

                    $directorInfo = DirectorInfo::where('id', $decryptedId)->first();
                    $businessInfo = BusinessInfo::where('id', $directorInfo->wp_business_info_id)->first();
                    $assetInfo = DirectorAsset::where('wp_director_info_id', $decryptedId)->first();
                    $liabilitiesInfo = DirectorLiabilities::where('wp_director_info_id', $decryptedId)->first();
                    $otherAssets = DirectorPropertiesAndOtherAssets::where('wp_director_info_id', $decryptedId)->get();
                    $contingentLib = DirectorContingentLiabilities::where('wp_director_info_id', $decryptedId)->get();
                    $householdIncome = DirectorHouseholdIncome::where('wp_director_info_id', $decryptedId)->get();
                    $unlistedShares = DirectorUnlistedShares::where('wp_director_info_id', $decryptedId)->get();

                    $html = view('sop.sop-pdf', ['signature' => $signatureInput, 'directorInfo' => $directorInfo, 'businessInfo' => $businessInfo, 'assetInfo' => $assetInfo, 'liabilitiesInfo' => $liabilitiesInfo, 'otherAssets' => $otherAssets, 'contingentLib' => $contingentLib, 'householdIncome' => $householdIncome, 'unlistedShares' => $unlistedShares])->render();

                    $mpdf = new Mpdf();
                    $mpdf->WriteHTML($html);
                    return $mpdf->Output('download.pdf', 'D');
                }

                return redirect()->back()->with('success_message', 'Form submitted and data saved successfully!');
            } catch (\Exception $e) {
                dd($e);
                // Log::error('Error updating or creating director and asset: ' . $e->getMessage());
                return redirect()->back()->with('error_message', 'There was an error saving the data. Please try again.');
            }
        }
    }

    // by suraj
    public function priceModel(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }
        
        return view('sop.sop-price-model')->with(compact('id'));
    }
    public function committeePaper(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }
        
        return view('businesses.committee-paper')->with(compact('id'));
    }

    public function migratefile()
    {
        // Check if the tables already exist to avoid duplication
        if (!Schema::hasTable('wp_director_assets')) {
            Schema::create('wp_director_assets', function (Blueprint $table) {
                // Primary key
                $table->bigIncrements('id');

                // Foreign key column
                $table->bigInteger('wp_director_info_id');

                // Other columns
                $table->string('account_or_regnumber', 100)->nullable();
                $table->string('cash_in_bank_and_deposit', 100)->nullable();
                $table->string('public_listed_shares', 100)->nullable();
                $table->tinyInteger('properties')->nullable();
                $table->string('motor_vehicles_boats', 100)->nullable();
                $table->string('other_cash_investments', 100)->nullable();
                $table->string('details_of_personal_pension', 100)->nullable();
                $table->string('other_assets', 100)->nullable();

                $table->foreign('wp_director_info_id', 'wp_director_assets_fk')
                    ->references('id')
                    ->on('wp_director_info')
                    ->onDelete('cascade');

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_director_liabilities')) {
            Schema::create('wp_director_liabilities', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('wp_director_info_id');
                $table->string('account_or_regnumber', 100)->nullable();
                $table->string('personal_loans_and_overdrafts', 100)->nullable();
                $table->string('mortgages', 100)->nullable();
                $table->string('credit_card_debts', 100)->nullable();
                $table->string('motor_loan', 100)->nullable();
                $table->string('property_rental', 100)->nullable();
                $table->string('other_debt_and_contingent_liabilities', 100)->nullable();
                $table->string('other_liabilities', 100)->nullable();

                $table->foreign('wp_director_info_id', 'wp_director_liabilities_fk')
                    ->references('id')
                    ->on('wp_director_info')
                    ->onDelete('cascade');

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_director_contingent_liabilities')) {
            Schema::create('wp_director_contingent_liabilities', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('wp_director_info_id');
                $table->string('creditor', 100)->nullable();
                $table->string('nature_of_pg', 100)->nullable();
                $table->string('unlimited_guarantee_or_limit_value', 100)->nullable();
                $table->foreign('wp_director_info_id', 'wp_director_contingent_lib_fk')
                    ->references('id')
                    ->on('wp_director_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_director_household_income')) {
            Schema::create('wp_director_household_income', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('wp_director_info_id');
                $table->string('type_and_source', 100)->nullable();
                $table->string('who_in_household', 100)->nullable();
                $table->string('gross_annual_income', 100)->nullable();
                $table->foreign('wp_director_info_id', 'wp_dir_household_income_fk')
                    ->references('id')
                    ->on('wp_director_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_director_properties_and_other_assets')) {
            Schema::create('wp_director_properties_and_other_assets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('wp_director_info_id');
                $table->string('property_address_and_assets', 100)->nullable();
                $table->string('estimated_value', 100)->nullable();
                $table->string('debt', 100)->nullable();
                $table->string('financing_costs', 100)->nullable();
                $table->string('income', 100)->nullable();

                $table->foreign('wp_director_info_id', 'wp_dir_properties_and_other_fk')
                    ->references('id')
                    ->on('wp_director_info')
                    ->onDelete('cascade');

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_director_unlisted_shares')) {
            Schema::create('wp_director_unlisted_shares', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('wp_director_info_id');
                $table->string('company_name', 100)->nullable();
                $table->string('reg_number', 100)->nullable();
                $table->string('status', 20)->nullable();
                $table->string('registered', 100)->nullable();
                $table->string('shareholding', 100)->nullable();
                $table->foreign('wp_director_info_id', 'wp_dir_unlisted_shares')
                    ->references('id')
                    ->on('wp_director_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Schema for wp_director_info modifications
        if (Schema::hasTable('wp_director_info')) {
            Schema::table('wp_director_info', function (Blueprint $table) {
                if (Schema::hasColumn('wp_director_info', 'phone')) {
                    $table->renameColumn('phone', 'mobile');
                }

                if (!Schema::hasColumn('wp_director_info', 'middle_name')) {
                    $table->text('middle_name')->nullable()->after('first_name');
                }

                if (!Schema::hasColumn('wp_director_info', 'time_in_curr_address')) {
                    $table->text('time_in_curr_address')->nullable()->after('wp_business_info_id');
                }

                if (!Schema::hasColumn('wp_director_info', 'tel_home')) {
                    $table->string('tel_home', 15)->nullable()->after('wp_business_info_id');
                }

                if (!Schema::hasColumn('wp_director_info', 'tel_business')) {
                    $table->string('tel_business', 15)->nullable()->after('wp_business_info_id');
                }

                if (!Schema::hasColumn('wp_director_info', 'declared_bankrupt')) {
                    $table->string('declared_bankrupt', 15)->nullable()->after('wp_business_info_id');
                }

                if (!Schema::hasColumn('wp_director_info', 'signature')) {
                    $table->string('signature', 100)->nullable()->after('wp_business_info_id');
                }
            });
        }

        // Return a response after migration
        return response()->json(['status' => 'Migration completed successfully!']);
    }


    public function loanInfo(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        if ($request->ajax()) {
            $data = DB::table('wp_loan_info')->where('user_id', $business_info->user_id);

            return DataTables::of($data)
                ->addColumn('index_data', function ($row) {

                    return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                </div>';
                })
                ->editColumn('loan_number', function ($row) {

                    return !empty($row->loan_number) ? $row->loan_number : '-';
                })
                ->editColumn('advance_requested', function ($row) {

                    return !empty($row->advance_requested) ? $row->advance_requested : '-';
                })
                ->editColumn('loan_purpose', function ($row) {

                    return !empty($row->loan_purpose) ? $row->loan_purpose : '-';
                })
                ->editColumn('deal_status', function ($row) {

                    return !empty($row->deal_status) ? $row->deal_status : '-';
                })
                ->editColumn('funded_date', function ($row) {

                    $funded_date = isset($row->funded_date) && !empty($row->funded_date) ? date("d-m-Y", strtotime($row->funded_date)) : '-';

                    return $funded_date;
                })
                ->addColumn('row_url', function ($row) use ($id) {

                    return route('businesses.loan.info.add', [$id, $row->id]);
                })
                ->addColumn('actions', function ($row) use ($id) {

                    return '<div class="action-and-check">
                                    <div class="form-check form-checkbox-dark d-inline-block">
                                        <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                        <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                    </div>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-' . $row->id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-' . $row->id . '">
                                            <a class="dropdown-item" href="' . route('businesses.loan.info.add', [$id, $row->id]) . '">Edit</a>
                                            <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="' . $row->id . '">Remove</a>
                                        </div>
                                    </div>
                                </div>';
                })
                ->rawColumns(['index_data', 'loan_number', 'advance_requested', 'loan_purpose', 'deal_status', 'funded_date', 'actions'])
                ->filterColumn('filter_index', function ($query, $keyword) {
                    $query->orWhereRaw("loan_number like ?", ["%{$keyword}%"])
                        ->orWhereRaw("deal_status like ?", ["%{$keyword}%"]);
                })
                ->order(function ($query) {

                    $query->orderBy('id', 'asc');
                })
                ->make(true);
        }

        return view('businesses.add.loan-info.list')->with(compact('id'));
    }

    public function loanInfoAdd(Request $request, $id, $d_id = null)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        $data = null;

        if (!empty($d_id)) {
            $data = LoanInfo::where('id', $d_id)->first();
        }

        if (request()->method() == 'GET') {
            return view('businesses.add.loan-info.add')->with(compact('data', 'id'));
        }

        $validations = [
            'loan_number' => 'required',
            'funded_date' => 'required',
        ];

        if (!empty($data)) {
            $validations['loan_type'] = 'required';
            $validations['minimum_daily_repayment'] = 'required';
            $validations['deal_status'] = 'required';
            $validations['business_unit_type'] = 'required';
            $validations['bca_payment_frequency_type_id'] = 'required';
            $validations['CollectionDay'] = 'required';
        }

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $not_created_yet = false;

        if (empty($data)) {
            $not_created_yet = true;
            $data = new LoanInfo();
            $data->user_id = $business_info->user_id;
            $data->loan_status_message = $request->loan_status_message;
        }

        $data->loan_number = $request->loan_number;
        $data->loan_type = !empty($request->loan_type) ? $request->loan_type : '';
        $data->advance_requested = $request->advance_requested;
        $data->loan_purpose = $request->loan_purpose;
        $data->status = !empty($request->_status) ? $request->_status : '';
        $data->original_default_balance = $request->original_default_balance;
        $data->minimum_daily_repayment = $request->minimum_daily_repayment;
        $data->revenue_repayment_rate = !empty($request->revenue_repayment_rate) ? $request->revenue_repayment_rate : '';
        $data->repayment_term_in_working_days = !empty($request->repayment_term_in_working_days) ? $request->repayment_term_in_working_days : '';
        $data->broker_id = $request->broker_id;
        $data->deal_status = $request->deal_status;
        $data->aip_status = $request->aip_status;
        $data->business_unit_type = $request->business_unit_type;
        $data->bca_payment_frequency_type_id = !empty($request->bca_payment_frequency_type_id) ? $request->bca_payment_frequency_type_id : '';
        $data->analyst = $request->analyst;
        $data->live = !empty($request->live) ? $request->live : '';
        $data->UnderwritingRevAvg = $request->UnderwritingRevAvg;
        $data->CollectionDay = $request->CollectionDay;
        $data->outstanding_balance = $request->outstanding_balance;
        $data->arrangement_fee = $request->arrangement_fee;
        $data->repayment_amount = $request->repayment_amount;
        $data->committee_member = $request->committee_member;
        $data->status_code = $request->status_code;
        $data->debenture = $request->debenture;
        $data->funded_date = $request->funded_date;
        $data->close_date = $request->close_date;
        $data->committee_date = $request->committee_date;
        $data->loan_status_message = !empty($request->loan_status_message) ? $request->loan_status_message : '';
        $data->further_info_required = $request->further_info_required;
        $data->next_step = $request->next_step;

        $data->save();

        if ($not_created_yet) {
            return redirect()->route('businesses.loan.info.add', [$id, $data->id])->with('message', 'New loan created as ' . $data->first_name);
        }

        return redirect()->route('businesses.loan.info.add', [$id, $data->id])->with('message', 'Loan ' . $data->first_name . ' updated');
    }

    public function loanInfoRemove(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        LoanInfo::whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }

    public function directorSearch(Request $request)
    {
        $directors = [];

        if (!empty($request->fname) || !empty($request->lname) || !empty($request->dob)) {
            $directors = Helper::getDirectorSearchResults($request->fname, $request->lname, $request->dob, !empty($request->country) ? $request->country : 'GB');
        }

        return view('businesses.director-search')->with(compact('directors'));
    }

    public function openBankingAccounts(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        if (request()->method() == 'GET') {
            $open_banking_details = DB::table('wp_open_banking_details')->where('wp_business_info_id', $id)->get();
            $ob_accounts = DB::table('tblAccountsTable')->get();

            return view('businesses.add.open-banking-accounts')->with(compact('id', 'open_banking_details', 'ob_accounts'));
        }

        if (isset($request->open_banking_account_id) && !empty($request->open_banking_account_id)) {
            $open_banking_details = DB::table('wp_open_banking_details')->where('wp_business_info_id', $id)->delete();

            foreach ($request->open_banking_account_id as $key => $ob_account_id) {
                if (!empty($ob_account_id)) {
                    $check_open_banking = DB::table('wp_open_banking_details')->where('tbl_account_id', $ob_account_id)->where('wp_business_info_id', $id)->first();

                    if (empty($check_open_banking)) {
                        DB::table('wp_open_banking_details')->insert(['tbl_account_id' => $ob_account_id, 'wp_business_info_id' => $id]);
                    }
                }
            }
        }

        return redirect()->route('businesses.open.banking.accounts', $id)->with('message', 'Open banking account details updated for ' . $business_info->business_name);
    }

    public function committeeMeeting(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        $data = CommitteeMeeting::where('user_id', $business_info->user_id)->where('ref_no', $business_info->ref_no)->first();

        if (!empty($data)) {
            $committee_opinions = DB::table('committee_opinion')->where('meeting_id', $data->id)->get();
        } else {
            $data = new CommitteeMeeting();
            $data->ref_no = $business_info->ref_no;
            $data->user_id = $business_info->user_id;
            $data->save();

            $data = CommitteeMeeting::where('id', $data->id)->first();

            $committee_members = DB::table('committee_members')->get();

            $committee_opinions = [];

            foreach ($committee_members as $key => $committee_member) {
                $committee_opinion_id = DB::table('committee_opinion')->insertGetId([

                    'meeting_id' => $data->id,
                    'mem_id' => $committee_member->id,
                    'mem_name' => $committee_member->member_name,
                ]);

                $committee_opinions[] = [

                    'id' =>  $committee_opinion_id,
                    'meeting_id' => $data->id,
                    'mem_id' => $committee_member->id,
                    'mem_name' => $committee_member->member_name,
                    'rationale' => "",
                    'vote' => "",
                ];
            }
        }

        if (request()->method() == 'GET') {
            return view('businesses.add.committee-meeting')->with(compact('data', 'committee_opinions', 'id'));
        }

        $validations = [
            'meeting_date' => 'required',
            'credit_strengths' => 'required',
            'credit_weekness' => 'required',
            'info_required' => 'required',
            'major_reservations' => 'required',
            'decision' => 'required',
            'video_name' => 'mimes:mp4,avi,mov,wmv|max:50000',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data->meeting_date = $request->meeting_date;
        $data->credit_strengths = $request->credit_strengths;
        $data->credit_weekness = $request->credit_weekness;
        $data->info_required = $request->info_required;
        $data->major_reservations = $request->major_reservations;
        $data->decision = $request->decision;

        if (isset($request->video_name) && !empty($request->file('video_name'))) {
            $file = $request->file('video_name');

            if ($file->isValid()) {
                $path = $file->store('public/files/committee-meeting');
                $file_path = str_replace('public/', '', $path);

                $data->video_name = $file_path;
            }
        }

        $data->save();

        if (!empty($request->c_option_id)) {
            foreach ($request->c_option_id as $key => $c_option) {
                $committee_opinion = DB::table('committee_opinion')->where('id', $c_option)->update([

                    'vote' => $request->vote[$key],
                    'rationale' => $request->rationale[$key],
                ]);
            }
        }

        return redirect()->route('businesses.committee.meeting', $id)->with('message', 'Committee Meeting updated');
    }

    public function cardPayment(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        if ($request->ajax()) {
            $data = DB::table('ga_recurring_payments')->where('user_id', $business_info->user_id);

            return DataTables::of($data)
                ->addColumn('index_data', function ($row) {

                    return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                </div>';
                })
                ->editColumn('created_at', function ($row) {

                    return $row->created_at ? Carbon::parse($row->created_at)->format('d/m/Y h:i A') : '-';
                })
                ->editColumn('payment_type', function ($row) {

                    $payment_type = null;

                    if ($row->payment_type == 1) {
                        $payment_type = 'Single Payment';
                    } else if ($row->payment_type == 2) {
                        $payment_type = 'Recurring Payment';
                    }

                    return $payment_type;
                })
                ->editColumn('recurring_payment_time', function ($row) {

                    return $row->payment_type != 1 && !empty($row->recurring_payment_time) ? Carbon::parse($row->recurring_payment_time)->format('h:i A') : '-';
                })
                ->editColumn('amount', function ($row) {

                    $amount = Helper::displayPrice($row->amount);
                    return !empty($amount) ? $amount : '-';
                })
                ->editColumn('installments', function ($row) {

                    return $row->payment_type != 1 && !empty($row->installments) ? $row->installments : '-';
                })
                ->editColumn('recurring_payment', function ($row) {

                    return $row->payment_type != 1 && !empty($row->recurring_payment) ? ucwords($row->recurring_payment) : '-';
                })
                ->editColumn('recurring_payment_day_of_week', function ($row) {

                    if ($row->payment_type == 1 || empty($row->recurring_payment_day_of_week) || empty($row->recurring_payment) || $row->recurring_payment == 'daily') {
                        return '-';
                    }

                    $recurring_payment_day_of_week = json_decode($row->recurring_payment_day_of_week);

                    $day = [];

                    foreach ($recurring_payment_day_of_week as $week_day) {
                        if ($week_day == 2) {
                            $day[] = 'Monday';
                        } else if ($week_day == 3) {
                            $day[] = 'Tuesday';
                        } else if ($week_day == 4) {
                            $day[] = 'Wednesday';
                        } else if ($week_day == 5) {
                            $day[] = 'Thursday';
                        } else if ($week_day == 6) {
                            $day[] = 'Friday';
                        } else if ($week_day == 7) {
                            $day[] = 'Saturday';
                        } else if ($week_day == 1) {
                            $day[] = 'Sunday';
                        }
                    }

                    if (empty($day)) {
                        return '-';
                    }

                    return implode(', ', $day);
                })
                ->addColumn('row_url', function ($row) use ($id) {

                    return;
                })
                ->addColumn('actions', function ($row) use ($id) {

                    return '<div class="action-and-check">
                                    <div class="form-check form-checkbox-dark d-inline-block">
                                        <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                        <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                    </div>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-' . $row->id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-' . $row->id . '">
                                            <a href="javascript:void(0)" class="dropdown-item fetch-dynamic-modal" data-url="' . route('businesses.card.payment.get', [$id, $row->id]) . '">Edit</a>
                                            <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="' . $row->id . '">Remove</a>
                                        </div>
                                    </div>
                                </div>';
                })
                ->rawColumns(['index_data', 'payment_type', 'recurring_payment_day_of_week', 'amount', 'installments', 'actions'])
                ->filterColumn('filter_index', function ($query, $keyword) {
                    $query->orWhereRaw("amount like ?", ["%{$keyword}%"])->orWhereRaw("installments like ?", ["%{$keyword}%"]);
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->make(true);
        }

        return view('businesses.add.card-payment.list')->with(compact('id'));
    }

    public function cardPaymentAdd(Request $request, $id, $p_id = null)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        $data = null;

        if (!empty($p_id)) {
            $data = CardPayment::where('id', $p_id)->first();
        }

        $validations = [
            'amount' => 'required',
            'payment_type' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $not_created_yet = false;

        if (empty($data)) {
            $not_created_yet = true;
            $data = new CardPayment();
            $data->created_at = now();
            $data->user_id = $business_info->user_id;
        }

        $data->amount = $request->amount;
        $data->currency = 'GBP';
        $data->payment_type = isset($request->payment_type) && !empty($request->payment_type) ? $request->payment_type : 1;

        if (isset($request->payment_type) && !empty($request->payment_type) && $request->payment_type == 2) {
            $validations = [
                'installments' => 'required',
            ];

            $validator = \Validator::make($request->all(), $validations);

            if ($validator->fails()) {
                return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
            }

            $data->installments = isset($request->installments) && !empty($request->installments) ? $request->installments : 0;
            $data->recurring_payment = isset($request->recurring_payment) && !empty($request->recurring_payment) ? $request->recurring_payment : 'weekly';
            $data->recurring_payment_time = isset($request->recurring_payment_time) && !empty($request->recurring_payment_time) ? $request->recurring_payment_time : '18:00:00';
            $data->recurring_payment_day_of_week = isset($request->recurring_payment_day_of_week) && !empty($request->recurring_payment_day_of_week) ? json_encode($request->recurring_payment_day_of_week) : json_encode(['2', '3', '4', '5', '6']);
        }

        $data->save();

        $customer = CustomerInfo::where('user_id', $business_info->user_id)->first();

        if (!empty($customer)) {
            if (isset($customer->email) && !empty($customer->email)) {
                $to = $customer->email;
            } else {
                $user = User::where('ID', $business_info->user_id)->first();
                $to = $user->user_email;
            }

            $link_url = Helper::$site_home_url . 'card-payment?ref=' . $data->id;

            Helper::sendEmail('emails.credit-payment', ['link_url' => $link_url], $to, 'Funding Alternative - Card Payment', [], null, null);

            return response()->json(['status' => 1, 'swal_message' => 'An Email has been sent to ' . $to]);
        } else {
            return response()->json(['status' => -1, 'swal_error_message' => 'Something went wrong!. Please refresh page and try again.']);
        }
    }

    public function cardPaymentGet(Request $request, $id, $p_id)
    {
        if (!$request->ajax()) {
            return redirect()->back();
        }

        if (empty($id)) {
            return response()->json(['status' => -1]);
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return response()->json(['status' => -1]);
        }

        if (empty($p_id)) {
            return response()->json(['status' => -1]);
        }

        $data = CardPayment::where('id', $p_id)->first();

        if (empty($data)) {
            return response()->json(['status' => -1]);
        }

        return response()->json(['status' => 1, 'modal' => view('businesses.add.card-payment.modals', ['data' => $data, 'id' => $id])->render()]);
    }

    public function cardPaymentRemove(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        CardPayment::whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }

    public function openBankingPayments(Request $request, $id)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        if ($request->ajax()) {
            $data = DB::table('tbltransactionslink')->where('user_id', $business_info->user_id);

            return DataTables::of($data)
                ->addColumn('index_data', function ($row) {

                    return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                </div>';
                })
                ->editColumn('created_at', function ($row) {

                    return $row->created_at ? Carbon::parse($row->created_at)->format('d/m/Y h:i A') : '-';
                })
                ->editColumn('recurring_start_day', function ($row) {

                    return !empty($row->recurring_start_day) ? $row->recurring_start_day : '-';
                })
                ->editColumn('recurring_expiry_date', function ($row) {

                    return $row->recurring_expiry_date ? Carbon::parse($row->recurring_expiry_date)->format('d/m/Y') : '-';
                })
                ->editColumn('payment_type', function ($row) {

                    $payment_type = $row->payment_type;

                    if ($row->payment_type == 'single_payment') {
                        $payment_type = 'Single Payment';
                    } else if ($row->payment_type == 'recurring_payment') {
                        $payment_type = 'Recurring Payment';
                    }

                    return $payment_type;
                })
                ->editColumn('payment_reference', function ($row) {

                    return !empty($row->payment_reference) ? $row->payment_reference  : '-';
                })
                ->editColumn('amount', function ($row) {

                    $amount = Helper::displayPrice($row->amount);
                    return !empty($amount) ? $amount : '-';
                })
                ->editColumn('recurring_payment', function ($row) {

                    return $row->payment_type != 1 && !empty($row->recurring_payment) ? ucwords($row->recurring_payment) : '-';
                })
                ->addColumn('row_url', function ($row) use ($id) {

                    return;
                })
                ->addColumn('actions', function ($row) use ($id) {

                    return '<div class="action-and-check">
                                    <div class="form-check form-checkbox-dark d-inline-block">
                                        <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-' . $row->id . '" value="' . $row->id . '">
                                        <label class="form-check-label no-rowurl-redirect" for="select-item-' . $row->id . '">&nbsp;</label>
                                    </div>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-' . $row->id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-' . $row->id . '">
                                            <a href="javascript:void(0)" class="dropdown-item fetch-dynamic-modal" data-url="' . route('businesses.open.banking.payments.get', [$id, $row->id]) . '">Edit</a>
                                            <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="' . $row->id . '">Remove</a>
                                        </div>
                                    </div>
                                </div>';
                })
                ->rawColumns(['index_data', 'payment_type', 'recurring_payment', 'amount', 'payment_reference', 'recurring_start_day', 'recurring_expiry_date', 'actions'])
                ->filterColumn('filter_index', function ($query, $keyword) {
                    $query->orWhereRaw("amount like ?", ["%{$keyword}%"])->orWhereRaw("payment_reference like ?", ["%{$keyword}%"]);
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->make(true);
        }

        return view('businesses.add.open-banking-payments.list')->with(compact('id'));
    }

    public function openBankingPaymentsAdd(Request $request, $id, $p_id = null)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        $data = null;

        if (!empty($p_id)) {
            $data = OpenBankingPayments::where('id', $p_id)->first();
        }

        $validations = [
            'amount' => 'required',
            'payment_type' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $not_created_yet = false;

        $link = Helper::getUUID('tbltransactionslink', 'link');

        if (empty($data)) {
            $not_created_yet = true;
            $data = new OpenBankingPayments();
            $data->created_at = now();
            $data->user_id = $business_info->user_id;
            $data->link = $link;
        }

        $data->amount = $request->amount;
        $data->payment_type = isset($request->payment_type) && !empty($request->payment_type) ? $request->payment_type : null;

        if (isset($request->payment_type) && !empty($request->payment_type) && $request->payment_type == 'Recurring Payment') {
            $validations = [
                'payment_reference' => 'required',
            ];

            $validator = \Validator::make($request->all(), $validations);

            if ($validator->fails()) {
                return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
            }

            $data->payment_reference = $request->payment_reference;
            $data->recurring_payment = $request->recurring_payment;
            $data->recurring_start_day = $request->recurring_start_day;
            $data->recurring_expiry_date = $request->recurring_expiry_date;
        }

        $data->save();

        $customer = CustomerInfo::where('user_id', $business_info->user_id)->first();

        if (!empty($customer)) {
            if (isset($customer->email) && !empty($customer->email)) {
                $to = $customer->email;
            } else {
                $user = User::where('ID', $business_info->user_id)->first();
                $to = $user->user_email;
            }

            $link_url = Helper::$site_home_url . 'obanking-payments?link=' . $data->link;

            Helper::sendEmail('emails.open-banking-payments', ['link_url' => $link_url], $to, 'Funding Alternative - Open Banking Payment', [], null, null);

            return response()->json(['status' => 1, 'swal_message' => 'An Email has been sent to ' . $to]);
        } else {
            return response()->json(['status' => -1, 'swal_error_message' => 'Something went wrong!. Please refresh page and try again.']);
        }
    }

    public function openBankingPaymentsGet(Request $request, $id, $p_id)
    {
        if (!$request->ajax()) {
            return redirect()->back();
        }

        if (empty($id)) {
            return response()->json(['status' => -1]);
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return response()->json(['status' => -1]);
        }

        if (empty($p_id)) {
            return response()->json(['status' => -1]);
        }

        $data = OpenBankingPayments::where('id', $p_id)->first();

        if (empty($data)) {
            return response()->json(['status' => -1]);
        }

        return response()->json(['status' => 1, 'modal' => view('businesses.add.open-banking-payments.modals', ['data' => $data, 'id' => $id])->render()]);
    }

    public function openBankingPaymentsRemove(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids)) {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        OpenBankingPayments::whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }
}
