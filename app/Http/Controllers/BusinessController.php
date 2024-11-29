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
use App\Helpers\DocumentService as DocumentService;
use App\Mail\SopSubmitted;
use Stevebauman\Location\Facades\Location;


use App\Models\DirectorAsset;
use App\Models\DirectorLiabilities;
use App\Models\AgreementToSendOut;
use App\Models\CommitteePaper;
use App\Models\CommitteePaperDirectorInfo;
use App\Models\CompleteOnceFunded;
use App\Models\DirectorContingentLiabilities;
use App\Models\DirectorHouseholdIncome;
use App\Models\DirectorPropertiesAndOtherAssets;
use App\Models\DirectorUnlistedShares;
use App\Models\Lender;
use App\Models\PreSendingOutTheAgreement;
use App\Models\PriceModel;
use App\Models\SifClient;
use App\Models\SifInvoice;
use App\Models\SifVerificationAndCompletion;
use App\Models\StatementOfPosition;
use Mpdf\Mpdf;
// use PhpOffice\PhpWord\PhpWord;
// use PhpOffice\PhpWord\IOFactory;
// use PhpOffice\PhpWord\Style\TablePosition;


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    // public function customerInfo(Request $request, $id = null)
    // {
    //     if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
    //         return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
    //     }

    //     $data = $not_created_yet = null;

    //     $business_info = BusinessInfo::where('id', $id)->first();

    //     if (empty($business_info)) {
    //         return redirect()->route('home');
    //     }

    //     $data = CustomerInfo::where('user_id', $business_info->user_id)->first();

    //     if (request()->method() == 'GET') {
    //         return view('businesses.add.customer-info')->with(compact('data', 'id', 'business_info'));
    //     }

    //     $validations = [
    //         'title' => 'required',
    //         'first_name' => 'required',
    //         'last_name' => 'required',
    //         'email' => 'required',
    //         'country' => 'required',
    //         'address' => 'required',
    //         'city' => 'required',
    //         'postal_code' => 'required',
    //     ];

    //     $validator = \Validator::make($request->all(), $validations);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validator);
    //     }

    //     $data->title = $request->title;
    //     $data->first_name = $request->first_name;
    //     $data->middle_name = $request->middle_name;
    //     $data->last_name = $request->last_name;
    //     $data->phone = $request->phone;
    //     $data->email = $request->email;
    //     $data->dob = $request->dob;
    //     $data->marital_status = $request->marital_status;
    //     $data->number_of_dependents = $request->number_of_dependents;
    //     $data->identification_document = $request->identification_document;
    //     $data->country = $request->country;
    //     // $data->county = $request->county;
    //     $data->address = $request->address;
    //     $data->city = $request->city;
    //     $data->state = $request->state;
    //     $data->postal_code = $request->postal_code;
    //     $data->years_at_address = $request->years_at_address;
    //     $data->role_in_business = $request->role_in_business;
    //     $data->ownership = $request->ownership;
    //     $data->floating_charge = $request->floating_charge;
    //     $data->banking_access = $request->banking_access;
    //     $data->save();

    //     return redirect()->route('businesses.customer.info', $id)->with('message', 'Customer information has been updated');
    // }

    public function customerInfo(Request $request, $id = null)
    {
        if (strtoupper(request()->method()) !== 'GET' && strtoupper(request()->method()) !== 'POST') {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        $data = $not_created_yet = null;

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        $data = CustomerInfo::where('user_id', $business_info->user_id)->first();


        if (request()->method() == 'GET') {
            return view('businesses.add.customer-info')->with(compact('data', 'id', 'business_info'));
        }

        $validations = [
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            // 'country' => 'required',
            // 'address' => 'required',
            // 'city' => 'required',
            // 'postal_code' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data->title = $request->title;
        $data->first_name = $request->first_name;
        $data->middle_name = $request->middle_name;
        $data->last_name = $request->last_name;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->dob = $request->dob;
        // $data->marital_status = $request->marital_status;
        // $data->number_of_dependents = $request->number_of_dependents;
        // $data->identification_document = $request->identification_document;
        // $data->country = $request->country;
        // $data->county = $request->county;
        // $data->address = $request->address;
        // $data->city = $request->city;
        // $data->state = $request->state;
        // $data->postal_code = $request->postal_code;
        // $data->years_at_address = $request->years_at_address;
        // $data->role_in_business = $request->role_in_business;
        $data->ownership = $request->ownership ?? '';
        $data->floating_charge = $request->floating_charge;
        $data->banking_access = $request->banking_access;
        $data->save();

        return redirect()->route('businesses.customer.info', $id)->with('message', 'Customer information has been updated');
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

        $gaCreditSafe = DB::table('ga_credit_safe')->where('id', $data->ga_credit_safe_id)->first();

        if (request()->method() == 'GET') {
            return view('businesses.add.business-info')->with(compact('data', 'id', 'gaCreditSafe'));
        }

        $validations = [
            'business_name' => 'required',
            'registration_number' => 'required',
            'switchboard_number' => 'nullable|numeric',
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
        $data->monthly_turnover = $request->monthly_turnover;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->postal_code = $request->postal_code;
        $data->country = $request->country;
        $data->county = $request->county;
        $data->switchboard_number = $request->switchboard_number;
        $data->checked_address = isset($request->checked_address) && !empty($request->checked_address) ? true : false;

        if (!empty($request->checked_address) && strtolower($request->checked_address) === 'on') {
            $data->traddress = $request->address ?? $data->traddress;
            $data->trcity = $request->city ?? $data->trcity;
            $data->trpostal_code = $request->postal_code ?? $data->trpostal_code;
            $data->trcountry = $request->country ?? $data->trcountry;
            $data->trcounty = $request->county ?? $data->trcounty;
        } else {
            $data->traddress = $request->traddress ?? $data->traddress;
            $data->trcity = $request->trcity ?? $data->trcity;
            $data->trpostal_code = $request->trpostal_code ?? $data->trpostal_code;
            $data->trcountry = $request->trcountry ?? $data->trcountry;
            $data->trcounty = $request->trcounty ?? $data->trcounty;
        }


        $data->website_address = $request->website_address;
        $data->reason_for_funding = $request->reason_for_funding;

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
                ->addColumn('sop_button', function ($row) {
                    $sop_link = isset($row->sop) && !empty($row->sop) ? $row->sop : '';
                    if ($sop_link) {
                        return '<div class="dt-btns">
                                <a href="' . $sop_link . '" class="btn btn-sm btn-custom">Completed</a>
                            </div>';
                    } else {
                        return null;
                    }
                })->addColumn('actions', function ($row) use ($id) {

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
                ->rawColumns(['index_data', 'address_simple_value', 'date_of_birth', 'date_appointed', 'actions', 'sop_button'])
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
            'mobile' => 'max:15',
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
        $data->mobile = $request->mobile;
        $data->email = $request->email;
        $data->surname = $request->surname;
        $data->address_type = $request->address_type;
        $data->address_simple_value = $request->address_simple_value;
        $data->address_line_1 = $request->address_street;
        $data->address_line_2 = $request->address_city;
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
            return redirect()->route('home');
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

            return view('sop.sop-form', ['id' => $decryptedId, 'directorInfo' => $directorInfo, 'businessInfo' => $businessInfo, 'assetInfo' => $assetInfo, 'liabilitiesInfo' => $liabilitiesInfo, 'otherAssets' => $otherAssets, 'contingentLib' => $contingentLib, 'householdIncome' => $householdIncome, 'unlistedShares' => $unlistedShares]);
        }

        if ($request->isMethod('POST')) {

            $rules = [];
            $attributes = [];

            $fieldMappings = [
                'has_properties' => [
                    'key' => 'otherAssets.*.property_address_and_assets',
                    'rule' => 'required',
                    'attribute' => '"Property Address(es)"',
                ],
                'has_contingentLib' => [
                    'key' => 'contingentLib.*.creditor',
                    'rule' => 'required',
                    'attribute' => '"Creditor"',
                ],
                'has_householdIncome' => [
                    'key' => 'householdIncome.*.type_and_source',
                    'rule' => 'required',
                    'attribute' => '"Type and sources"',
                ],
                'has_unlistedShares' => [
                    'key' => 'unlistedShares.*.company_name',
                    'rule' => 'required',
                    'attribute' => '"Company Name"',
                ],
            ];


            $rules['proposed_guarantor_details_title'] = 'required|alpha';
            $attributes['proposed_guarantor_details_title'] = '"Title"';

            $rules['proposed_guarantor_details_first_name'] = 'required|alpha';
            $attributes['proposed_guarantor_details_first_name'] = '"First Name"';

            $rules['proposed_guarantor_details_middle_name'] = 'nullable|alpha';
            $attributes['proposed_guarantor_details_middle_name'] = '"Middle Name"';

            $rules['proposed_guarantor_details_surname'] = 'required|alpha';
            $attributes['proposed_guarantor_details_surname'] = '"Last Name"';

            $rules['proposed_guarantor_details_email'] = 'nullable|email';
            $attributes['proposed_guarantor_details_email'] = '"Email"';

            $rules['proposed_guarantor_details_tel_home'] = 'nullable|numeric';
            $attributes['proposed_guarantor_details_tel_home'] = '"Tel Home"';

            $rules['proposed_guarantor_details_tel_business'] = 'nullable|numeric';
            $attributes['proposed_guarantor_details_tel_business'] = '"Tel Business"';

            $rules['proposed_guarantor_details_mobile'] = 'nullable|numeric';
            $attributes['proposed_guarantor_details_mobile'] = '"Mobile"';

            $rules['proposed_guarantor_details_next_of_kin_full_name'] = 'nullable|regex:/^[a-zA-Z\s]+$/';
            $attributes['proposed_guarantor_details_next_of_kin_full_name'] = '"Next of Kin Full Name"';

            $rules['proposed_guarantor_details_next_of_kin_mobile_number'] = 'nullable|numeric';
            $attributes['proposed_guarantor_details_next_of_kin_mobile_number '] = '"Next of Kin Mobile Number"';

            $rules['proposed_guarantor_details_next_of_kin_email_address'] = 'nullable|email';
            $attributes['proposed_guarantor_details_next_of_kin_email_address'] = '"Next of Kin Email Address"';


            foreach ($fieldMappings as $inputKey => $mapping) {
                $inputValue = $request->input($inputKey);
                if ($inputValue === 'y') {
                    $rules[$mapping['key']] = $mapping['rule'];
                    $attributes[$mapping['key']] = $mapping['attribute'];
                } else {
                    Log::warning("Field $inputKey is not 'y', value: " . json_encode($inputValue));
                }
            }

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($attributes);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }


            try {
                DB::transaction(function () use ($request, $decryptedId, $directorInfo) {

                    if (!empty($directorInfo)) {
                        $directorInfo->name = trim($request->proposed_guarantor_details_title . ' ' . $request->proposed_guarantor_details_first_name . ' ' . $request->proposed_guarantor_details_middle_name . ' ' . $request->proposed_guarantor_details_surname);
                        $directorInfo->title = $request->proposed_guarantor_details_title;
                        $directorInfo->first_name = $request->proposed_guarantor_details_first_name;
                        $directorInfo->middle_name = $request->proposed_guarantor_details_middle_name;
                        $directorInfo->surname = $request->proposed_guarantor_details_surname;
                        $directorInfo->email = $request->proposed_guarantor_details_email;
                        $directorInfo->mobile = $request->proposed_guarantor_details_mobile;
                        $directorInfo->tel_home = $request->proposed_guarantor_details_tel_home;
                        $directorInfo->tel_business = $request->proposed_guarantor_details_tel_business;
                        $directorInfo->house_number = $request->proposed_guarantor_details_house_number;
                        $directorInfo->address_line_1 = $request->proposed_guarantor_details_address_line_1;
                        $directorInfo->address_line_2 = $request->proposed_guarantor_details_address_line_2;
                        $directorInfo->address_line_3 = $request->proposed_guarantor_details_address_line_3;
                        $directorInfo->address_postal_code = $request->proposed_guarantor_details_address_postal_code;
                        $directorInfo->address_simple_value = trim($request->proposed_guarantor_details_house_number . ' ' . $request->proposed_guarantor_details_address_line_1 . ' ' . $request->proposed_guarantor_details_address_line_2 . ' ' . $request->proposed_guarantor_details_address_line_3 . ' ' . $request->proposed_guarantor_details_address_postal_code);
                        $directorInfo->time_in_curr_address = $request->proposed_guarantor_details_time_in_curr_address;
                        $directorInfo->date_of_birth = $request->proposed_guarantor_details_date_of_birth;
                        $directorInfo->declared_bankrupt = $request->proposed_guarantor_details_declared_bankrupt;
                        $directorInfo->next_of_kin_email_address = $request->proposed_guarantor_details_next_of_kin_email_address;
                        $directorInfo->next_of_kin_mobile_number = $request->proposed_guarantor_details_next_of_kin_mobile_number;
                        $directorInfo->next_of_kin_full_name = $request->proposed_guarantor_details_next_of_kin_full_name;
                        $directorInfo->sop = url()->current();

                        $directorInfo->save();
                    }

                    $asset = DirectorAsset::updateOrCreate(
                        ['wp_director_info_id' => $decryptedId],

                        [
                            'account_or_regnumber' => $request->assets_account_or_regnumber ?? null,
                            'wp_director_info_id' => $decryptedId,
                            'cash_in_bank_and_deposit' => str_replace(',', '', $request->assets_cash_in_bank_and_deposit) ?? null,
                            'public_listed_shares' => str_replace(',', '', $request->assets_public_listed_shares) ?? null,
                            'properties' => (bool) $request->assets_properties ?? null,
                            'motor_vehicles_boats' => str_replace(',', '', $request->assets_motor_vehicles_boats) ?? null,
                            'other_cash_investments' => str_replace(',', '', $request->assets_other_cash_investments) ?? null,
                            'details_of_personal_pension' => str_replace(',', '', $request->assets_details_of_personal_pension) ?? null,
                            'other_assets' => str_replace(',', '', $request->assets_other_assets) ?? null,
                            'updated_at' => now()
                        ]
                    );

                    $liabilities = DirectorLiabilities::updateOrCreate(
                        ['wp_director_info_id' => $decryptedId],

                        [
                            'account_or_regnumber' => $request->liabilities_account_or_regnumber ?? null,
                            'wp_director_info_id' => $decryptedId,
                            'personal_loans_and_overdrafts' => str_replace(',', '', $request->liabilities_personal_loans_and_overdrafts) ?? null,
                            'mortgages' => str_replace(',', '', $request->liabilities_mortgages) ?? null,
                            'credit_card_debts' => str_replace(',', '', $request->liabilities_credit_card_debts) ?? null,
                            'motor_loan' => str_replace(',', '', $request->liabilities_motor_loan) ?? null,
                            'property_rental' => str_replace(',', '', $request->liabilities_property_rental) ?? null,
                            'other_debt_and_contingent_liabilities' => str_replace(',', '', $request->liabilities_other_debt_and_contingent_liabilities) ?? null,
                            'other_liabilities' => str_replace(',', '', $request->liabilities_other_liabilities) ?? null,
                            'updated_at' => now()
                        ]
                    );

                    $hasProperties = $request->input('has_properties') === 'y';
                    $otherAssetsData = $request->input('otherAssets');
                    DirectorPropertiesAndOtherAssets::where('wp_director_info_id', $decryptedId)->delete();

                    if ($hasProperties && !empty($otherAssetsData)) {
                        foreach ($otherAssetsData as $assetData) {
                            DirectorPropertiesAndOtherAssets::create([
                                'wp_director_info_id' => $decryptedId,
                                'property_address_and_assets' => $assetData['property_address_and_assets'],
                                'estimated_value' => str_replace(',', '', $assetData['estimated_value']) ?? null,
                                'debt' => str_replace(',', '', $assetData['debt']) ?? null,
                                'financing_costs' => str_replace(',', '', $assetData['financing_costs']) ?? null,
                                'income' => str_replace(',', '', $assetData['income']) ?? null,
                                'updated_at' => now()
                            ]);
                        }
                    }

                    $hasContingentLib = $request->input('has_contingentLib') === 'y';
                    $contingentLibData = $request->input('contingentLib');
                    DirectorContingentLiabilities::where('wp_director_info_id', $decryptedId)->delete();

                    if ($hasContingentLib && !empty($contingentLibData)) {
                        foreach ($contingentLibData as $contingentData) {
                            DirectorContingentLiabilities::create([
                                'wp_director_info_id' => $decryptedId,
                                'creditor' => $contingentData['creditor'],
                                'nature_of_pg' => $contingentData['nature_of_pg'] ?? null,
                                'unlimited_guarantee_or_limit_value' => str_replace(',', '', $contingentData['unlimited_guarantee_or_limit_value']) ?? null,
                                'updated_at' => now()
                            ]);
                        }
                    }

                    $hasHouseholdIncome = $request->input('has_householdIncome') === 'y';
                    $householdIncomeData = $request->input('householdIncome');
                    DirectorHouseholdIncome::where('wp_director_info_id', $decryptedId)->delete();

                    if ($hasHouseholdIncome && !empty($householdIncomeData)) {
                        foreach ($householdIncomeData as $householdIncome) {
                            DirectorHouseholdIncome::create([
                                'wp_director_info_id' => $decryptedId,
                                'type_and_source' => $householdIncome['type_and_source'],
                                'who_in_household' => $householdIncome['who_in_household'] ?? null,
                                'gross_annual_income' => str_replace(',', '', $householdIncome['gross_annual_income']) ?? null,
                                'updated_at' => now()
                            ]);
                        }
                    }

                    $hasUnlistedShares = $request->input('has_unlistedShares') === 'y';
                    $unlistedSharesData = $request->input('unlistedShares');
                    DirectorUnlistedShares::where('wp_director_info_id', $decryptedId)->delete();

                    if ($hasUnlistedShares && !empty($unlistedSharesData)) {
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
                    }
                });

                // Process Signature
                $signatureFileName = $this->saveSignature($request->input('signature'), $directorInfo->id);

                // Update wp_statement_of_position table
                $pdfName = $this->generateAndSendSOPdPdf($decryptedId);
                $ipAddress = $request->ip();
                $this->updateStatementOfPosition($directorInfo, $pdfName, $ipAddress);


                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved successfully',
                ]);
            } catch (\Exception $e) {
                // dd($e);
                Log::error('Error updating or creating director and asset: ' . $e->getMessage());
                return response()->json(['error_message' => 'Error creating document.', 'error-log' => $e->getMessage()], 400);
            }
        }
    }

    private function generateAndSendSOPdPdf($directorId)
    {
        $directorInfo = DirectorInfo::where('id', $directorId)->first();
        $businessInfo = BusinessInfo::where('id', $directorInfo->wp_business_info_id)->first();
        $assetInfo = DirectorAsset::where('wp_director_info_id', $directorId)->first();
        $totalSumAssetsVal = collect([
            is_numeric($assetInfo->cash_in_bank_and_deposit) ? $assetInfo->cash_in_bank_and_deposit : 0,
            is_numeric($assetInfo->public_listed_shares) ? $assetInfo->public_listed_shares : 0,
            is_numeric($assetInfo->motor_vehicles_boats) ? $assetInfo->motor_vehicles_boats : 0,
            is_numeric($assetInfo->other_cash_investments) ? $assetInfo->other_cash_investments : 0,
            is_numeric($assetInfo->details_of_personal_pension) ? $assetInfo->details_of_personal_pension : 0,
            is_numeric($assetInfo->other_assets) ? $assetInfo->other_assets : 0,
        ])->sum();

        $liabilitiesInfo = DirectorLiabilities::where('wp_director_info_id', $directorId)->first();
        $totalSumLiabilities = collect([
            is_numeric($liabilitiesInfo->personal_loans_and_overdrafts) ? $liabilitiesInfo->personal_loans_and_overdrafts : 0,
            is_numeric($liabilitiesInfo->mortgages) ? $liabilitiesInfo->mortgages : 0,
            is_numeric($liabilitiesInfo->credit_card_debts) ? $liabilitiesInfo->credit_card_debts : 0,
            is_numeric($liabilitiesInfo->motor_loan) ? $liabilitiesInfo->motor_loan : 0,
            is_numeric($liabilitiesInfo->property_rental) ? $liabilitiesInfo->property_rental : 0,
            is_numeric($liabilitiesInfo->other_debt_and_contingent_liabilities) ? $liabilitiesInfo->other_debt_and_contingent_liabilities : 0,
            is_numeric($liabilitiesInfo->other_liabilities) ? $liabilitiesInfo->other_liabilities : 0,
        ])->sum();
        $otherAssets = DirectorPropertiesAndOtherAssets::where('wp_director_info_id', $directorId)->get();
        $contingentLib = DirectorContingentLiabilities::where('wp_director_info_id', $directorId)->get();
        $householdIncome = DirectorHouseholdIncome::where('wp_director_info_id', $directorId)->get();
        $unlistedShares = DirectorUnlistedShares::where('wp_director_info_id', $directorId)->get();

        $html = view('sop.sop-pdf', ['directorInfo' => $directorInfo, 'businessInfo' => $businessInfo, 'assetInfo' => $assetInfo, 'liabilitiesInfo' => $liabilitiesInfo, 'otherAssets' => $otherAssets, 'contingentLib' => $contingentLib, 'householdIncome' => $householdIncome, 'unlistedShares' => $unlistedShares, 'totalSumAssetsVal' => $totalSumAssetsVal, 'totalSumLiabilities' => $totalSumLiabilities])->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $pdfName = trim("{$directorInfo->first_name} {$directorInfo->surname}") . " - SOP.pdf";
        $pdfPath = storage_path("app/public/{$pdfName}");
        $mpdf->Output($pdfPath, 'F');

        $recipientEmail = env('SOP_SUBMISSION_EMAIL');
        // $recipientEmail = 'kumarsrj2023@gmail.com';
        Mail::to($recipientEmail)->send(new SopSubmitted($directorInfo, $pdfPath, $businessInfo));

        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        return $pdfName;
    }

    public function downloadSOPPdf(Request $request, $directorId = null)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($directorId)) {
            return redirect()->route('home');
        }

        $directorInfo = DirectorInfo::where('id', $directorId)->first();

        if (empty($directorInfo)) {
            return redirect()->route('home');
        }

        $businessInfo = BusinessInfo::where('id', $directorInfo->wp_business_info_id)->first();
        $assetInfo = DirectorAsset::where('wp_director_info_id', $directorId)->first();
        $totalSumAssetsVal = collect([
            is_numeric($assetInfo->cash_in_bank_and_deposit ?? '') ? $assetInfo->cash_in_bank_and_deposit : 0,
            is_numeric($assetInfo->public_listed_shares ?? '') ? $assetInfo->public_listed_shares : 0,
            is_numeric($assetInfo->motor_vehicles_boats ?? '') ? $assetInfo->motor_vehicles_boats : 0,
            is_numeric($assetInfo->other_cash_investments ?? '') ? $assetInfo->other_cash_investments : 0,
            is_numeric($assetInfo->details_of_personal_pension ?? '') ? $assetInfo->details_of_personal_pension : 0,
            is_numeric($assetInfo->other_assets ?? '') ? $assetInfo->other_assets : 0,
        ])->sum();

        $liabilitiesInfo = DirectorLiabilities::where('wp_director_info_id', $directorId)->first();
        $totalSumLiabilities = collect([
            is_numeric($liabilitiesInfo->personal_loans_and_overdrafts ?? '') ? $liabilitiesInfo->personal_loans_and_overdrafts : 0,
            is_numeric($liabilitiesInfo->mortgages ?? '') ? $liabilitiesInfo->mortgages : 0,
            is_numeric($liabilitiesInfo->credit_card_debts ?? '') ? $liabilitiesInfo->credit_card_debts : 0,
            is_numeric($liabilitiesInfo->motor_loan ?? '') ? $liabilitiesInfo->motor_loan : 0,
            is_numeric($liabilitiesInfo->property_rental ?? '') ? $liabilitiesInfo->property_rental : 0,
            is_numeric($liabilitiesInfo->other_debt_and_contingent_liabilities ?? '') ? $liabilitiesInfo->other_debt_and_contingent_liabilities : 0,
            is_numeric($liabilitiesInfo->other_liabilities ?? '') ? $liabilitiesInfo->other_liabilities : 0,
        ])->sum();
        $otherAssets = DirectorPropertiesAndOtherAssets::where('wp_director_info_id', $directorId)->get();
        $contingentLib = DirectorContingentLiabilities::where('wp_director_info_id', $directorId)->get();
        $householdIncome = DirectorHouseholdIncome::where('wp_director_info_id', $directorId)->get();
        $unlistedShares = DirectorUnlistedShares::where('wp_director_info_id', $directorId)->get();

        $html = view('sop.sop-pdf', ['directorInfo' => $directorInfo, 'businessInfo' => $businessInfo, 'assetInfo' => $assetInfo, 'liabilitiesInfo' => $liabilitiesInfo, 'otherAssets' => $otherAssets, 'contingentLib' => $contingentLib, 'householdIncome' => $householdIncome, 'unlistedShares' => $unlistedShares, 'totalSumAssetsVal' => $totalSumAssetsVal, 'totalSumLiabilities' => $totalSumLiabilities])->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        // Prepare document name
        $directorName = trim("{$directorInfo->first_name} {$directorInfo->surname}");
        $businessName = $businessInfo->business_name;
        $documentTitle = "Statement Of Position";

        // Combine parts with proper formatting
        $pdfName = "{$directorName} – {$businessName} – {$documentTitle}.pdf";

        // Output the document with the new name
        return $mpdf->Output($pdfName, 'D');
    }

    private function fetchLocation($ipAddress)
    {
        try {
            $position = Location::get($ipAddress);
            if ($position) {
                return "{$position->cityName}, {$position->regionName}, {$position->countryName}";
            }
            return 'Unknown Location';
        } catch (\Exception $e) {
            Log::error("Error fetching location for IP {$ipAddress}: " . $e->getMessage());
            return 'Unable to Fetch Location';
        }
    }

    private function saveSignature($signatureInput, $directorInfoId)
    {
        $signaturesPath = public_path('signatures');
        if (!file_exists($signaturesPath)) {
            mkdir($signaturesPath, 0777, true);
        }

        $fileName = '';
        $filePath = '';

        if (!empty($signatureInput)) {
            if (preg_match('/^data:image\/(\w+);base64,/', $signatureInput, $type)) {
                $imageType = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);

                $base64Data = substr($signatureInput, strpos($signatureInput, ',') + 1);
                $decodedImage = base64_decode($base64Data);

                $fileName = 'signature_' . $directorInfoId . '.' . $imageType;
                $filePath = $signaturesPath . '/' . $fileName;

                // Save the image to the file
                file_put_contents($filePath, $decodedImage);

                // Update database with the file name
                DB::table('wp_director_info')
                    ->where('id', $directorInfoId)
                    ->update(['signature' => $fileName]);

                return $fileName;
            }
            return null;
        } else {
            // Retrieve director info
            $directorInfo = DB::table('wp_director_info')
                ->where('id', $directorInfoId)
                ->first();

            // Reset signature in the database if it exists
            if (!empty($directorInfo->signature)) {
                DB::table('wp_director_info')
                    ->where('id', $directorInfoId)
                    ->update(['signature' => '']);
            }

            // Check and delete all matching files (signature_[id].*)
            if (!empty($directorInfo->signature)) {
                // Generate file name pattern (e.g., signature_[id].*)
                $filePattern = $signaturesPath . '/signature_' . $directorInfoId . '.*';

                // Use glob to find matching files
                $files = glob($filePattern);

                // Loop through and delete all matching files
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        unlink($file); // Delete the file
                    }
                }
            }
            return null;
        }
    }

    private function updateStatementOfPosition($directorInfo, $pdfName, $ipAddress)
    {
        $location = $this->fetchLocation($ipAddress);

        $statementData = [
            'wp_director_info_id' => $directorInfo->id,
            'director_name' => trim("{$directorInfo->first_name} {$directorInfo->surname}"),
            'email' => $directorInfo->email,
            'ip_address' => $ipAddress,
            'submitted_by' => now(),
            'location' => $location,
            'document_name' => $pdfName,
        ];

        StatementOfPosition::updateOrCreate(
            ['wp_director_info_id' => $directorInfo->id],
            $statementData
        );
    }

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

        if ($request->ajax()) {
            try {

                $this->savePriceModel($request->all(), $id);
                $updatedPriceModelData = PriceModel::where('wp_business_info_id', $id)->first();

                return response()->json([
                    'success' => true,
                    'message' => 'Data submitted successfully',
                    'priceModelData' => $updatedPriceModelData
                ], 200);
            } catch (\Exception $e) {
                // dd($e);
                Log::error('Error in getLoanData: ' . $e->getMessage());
                return response()->json(['status' => false, 'message' => 'An error occurred while processing your request.'], 500);
            }
        }
        $priceModelData = PriceModel::where('wp_business_info_id', $id)->first();

        return view('sop.sop-price-model')->with(['id' => $id, 'priceModelData' => $priceModelData]);
    }

    private function savePriceModel(array $data, $id)
    {
        $repaymentStructure = $data['repaymentStructure'] ?? [];
        $appRevenueCheker = $data['appRevenueCheker'] ?? [];
        $repaymentSchedule = $data['repaymentSchedule'] ?? [];
        $deal = $data['deal'] ?? [];

        $priceModel = PriceModel::updateOrCreate(
            ['wp_business_info_id' => $id],
            [
                'advance_requested' => $repaymentStructure['advanceRequested'] ?? '',
                'repayment_period' => $repaymentStructure['repaymentPeriodMonths'] ?? '',
                'multiple' => $repaymentStructure['multiple'] ?? '',
                'property_equity' => $repaymentStructure['propertyEquity'] ?? '',
                'average_monthly_revenue' => $appRevenueCheker['averageMonthlyRevenue'] ?? '',
                'arrangement_fee_excl_VAT' => $repaymentStructure['arrangementVATExcl'] ?? '',
                'arrangement_fee_incl_VAT' => $repaymentStructure['arrangementVATIncl'] ?? '',
                'total_repayable' => $repaymentStructure['maxRepayment'] ?? '',
                'pre_introducer_IRR' => $repaymentSchedule['weekly']['preIntroducerIRR'] ?? '',
                'post_introducer_IRR' => $repaymentSchedule['weekly']['postIntroducerIRR'] ?? '',
                'duration' => $repaymentStructure['repaymentPeriodWeeks'] ?? '',
                'how_long_until_breakeven' => $repaymentStructure['weeksToBreakeven'] ?? '',
                'rate_of_income' => $deal['netProfit'],
                'fixed_repayment_amount' => $repaymentStructure['expWeeklyRepay'],
                'daily_repayment_amount' => $repaymentStructure['expDailyRepay'],
                'commission' => $deal['commission'],
                'refinance' => $repaymentStructure['refinance'],
            ]
        );
    }

    public function committeePaper(Request $request, $id)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
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
            $business_info = BusinessInfo::find($id);

            if (!$business_info) {
                return response()->json(['error' => 'Business info not found'], 404);
            }

            // Fetch loan data
            $data = DB::table('wp_loan_info')->where('user_id', $business_info->user_id);

            return DataTables::of($data)
                ->addColumn('checkbox', function ($row) {
                    return '<div class="form-check form-checkbox-dark">
                            <input type="checkbox" class="form-check-input select-loan-item-checkbox"
                                id="select-loan-' . $row->id . '"
                                value="' . $row->id . '"
                                x-model="selectedLoan" 
                                @change="selectedLoan = (selectedLoan === ' . $row->id . ') ? null : ' . $row->id . '"
                                :checked="selectedLoan === ' . $row->id . '">
                            <label class="form-check-label no-rowurl-redirect" for="select-loan-' . $row->id . '">&nbsp;</label>
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
                    return !empty($row->funded_date) ? $row->funded_date : '-';
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }

        return view('businesses.committee-paper', compact('id'));
    }

    public function getLoanData(Request $request, $id)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
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
            try {
                $response = [];
                $loanId = $request->input('loanId');
                $loanInfo = DB::table('wp_loan_info')->where('id', $loanId)->first() ?: [];

                $response['businessInfo'] = $business_info;
                if ($loanInfo) {
                    $response['loanInfo'] = $loanInfo ?: [];
                    $response['wpIntroducersInfo'] = DB::table('wp_introducers_info')->where('id', $loanInfo->broker_id)->first() ?: [];
                    $response['bcaPaymentFrequencyTypes'] = DB::table('bca_payment_frequency_types')->get()->toArray() ?: [];
                    $response['bcaRePaymentTypes'] = DB::table('bca_repayment_types')->get()->toArray() ?: [];
                    $response['wpCommitteePaper'] = DB::table('wp_committee_paper')->where('wp_loan_info_id', $loanId)->where('wp_business_info_id', $id)->first() ?: [];
                }
                $response['gaCreditSafe'] = DB::table('ga_credit_safe')->where('id', $business_info->ga_credit_safe_id)->first() ?: [];
                $response['gaCreditSafeShareHolders'] = DB::table('ga_credit_safe_share_holders')->where('ga_credit_safe_id', $business_info->ga_credit_safe_id)->get()->toArray();
                $response['directors'] = DB::table('wp_director_info')->where('wp_business_info_id', $id)->orderBy('id', 'asc')->get()->toArray() ?: [];

                $directorIds = array_column($response['directors'], 'id');
                $response['propertiesAndAssets'] = DB::table('wp_director_properties_and_other_assets')
                    ->whereIn('wp_director_info_id', $directorIds)
                    ->orderBy('wp_director_info_id', 'asc')
                    ->get()
                    ->groupBy('wp_director_info_id');

                foreach ($response['directors'] as &$director) {
                    $director->properties_and_other_assets = $response['propertiesAndAssets']->get($director->id, []);
                }
                unset($director);

                $response['priceModelData'] = PriceModel::where('wp_business_info_id', $id)->first() ?: [];
                $response['gaCreditSafeCountyCourtJudgements'] = DB::table('ga_credit_safe_county_court_judgements')->where('ga_credit_safe_id', $business_info->ga_credit_safe_id)->get();
                // $response['gaCreditSafeCountyCourtJudgements'] = DB::table('ga_credit_safe_county_court_judgements')->where('ga_credit_safe_id', 176)->get();

                return response()->json(['status' => true, 'data' => $response]);
            } catch (\Exception $e) {
                Log::error('Error in getLoanData: ' . $e->getMessage());
                return response()->json(['status' => false, 'message' => 'An error occurred while processing your request.', 'error_log' => $e->getMessage()], 500);
            }
        }
        return redirect()->route('home');
    }

    public function saveCommitteePaperData(Request $request, $id)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
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

            $businessInfo = !empty($request->input('businessInfo')) ? $request->input('businessInfo') : [];
            $loanId = !empty($request->input('loanId')) ? $request->input('loanId') : [];
            $directors = !empty($request->input('directors')) ? $request->input('directors') : [];
            $gaCreditSafe = !empty($request->input('gaCreditSafe')) ? $request->input('gaCreditSafe') : [];
            // $gaCreditSafeCountyCourtJudgements = !empty($request->input('gaCreditSafeCountyCourtJudgements')) ? $request->input('gaCreditSafeCountyCourtJudgements') : [];
            // $gaCreditSafeShareHolders = !empty($request->input('gaCreditSafeShareHolders')) ? $request->input('gaCreditSafeShareHolders') : [];
            $loanInfo = !empty($request->input('loanInfo')) ? $request->input('loanInfo') : [];

            $wpIntroducersInfo = !empty($request->input('wpIntroducersInfo')) ? $request->input('wpIntroducersInfo') : [];
            $priceModelData = !empty($request->input('priceModelData')) ? $request->input('priceModelData') : [];
            $wpCommitteePaper = !empty($request->input('wpCommitteePaper')) ? $request->input('wpCommitteePaper') : [];
            $newData = !empty($request->input('newData')) ? $request->input('newData') : [];

            try {
                DB::beginTransaction();

                $priceModel = CommitteePaper::updateOrCreate(
                    ['wp_business_info_id' => $businessInfo['id'], 'wp_loan_info_id' => $loanId],
                    [
                        'wp_business_info_id' => $businessInfo['id'],
                        'wp_loan_info_id' => $loanId,
                        'business_name' => $gaCreditSafe['compSum_businessName'] ?? '',
                        'introduction' => $wpIntroducersInfo['broker_name'] ?? '',
                        'company_number' => $gaCreditSafe['compSum_companyRegistrationNumber'] ?? '',
                        'amount_funding' => $loanInfo['advance_requested'] ?? '',
                        'registered_address' => $gaCreditSafe['compIdentiBasicInfo_contactAddress_simpleValue'] ?? '',
                        'servicing_and_administration_fee' => $priceModelData['arrangement_fee_excl_VAT'] ?? '',
                        'date_of_incorporation' => $gaCreditSafe['compIdentiBasicInfo_companyRegistrationDate'] ?? '',
                        'multiple' => $priceModelData['multiple'] ?? '',
                        'website' => $wpCommitteePaper['website'] ?? '',
                        'total_repayable' => $priceModelData['total_repayable'] ?? '',
                        'shareholding_and_directors' => '', // none
                        'allocation_of_the_funds' => $wpCommitteePaper['allocation_of_the_funds'] ?? '',
                        'personal_guarantees' => $wpCommitteePaper['personal_guarantees'] ?? '',
                        'pre_introducer_IRR' => $priceModelData['pre_introducer_IRR'] ?? '',
                        'post_introducer_IRR' => $priceModelData['post_introducer_IRR'] ?? '',
                        'how_they_make_their_money' => $wpCommitteePaper['how_they_make_their_money'] ?? '',
                        'why_irr_chosen' => $wpCommitteePaper['why_irr_chosen'] ?? '',
                        'duration' => $priceModelData['duration'] ?? '',
                        'how_long_until_breakeven' => $priceModelData['how_long_until_breakeven'] ?? '',
                        'rate_of_income' => $priceModelData['rate_of_income'] ?? '',
                        'creditsafe_status' => $gaCreditSafe['compSum_companyStatus_description'] ?? '',
                        'repayment_frequency' => $loanInfo['bca_payment_frequency_type_id'] ?? '',
                        'active_ccjs' => '', //none
                        'repayment_type' => $loanInfo['bca_repayment_type_id'] ?? '',
                        'weighted_scorecard' => $wpCommitteePaper['weighted_scorecard'] ?? '',
                        'fixed_repayment_amount' => $priceModelData['fixed_repayment_amount'] ?? '',
                        'ga_credit_safe_id' => $gaCreditSafe['id'] ?? '',
                    ]
                );

                $existingLoanData = DB::table('wp_loan_info')->where('id', $loanId)->first();
                if ($existingLoanData) {
                    DB::table('wp_loan_info')
                        ->where('id', $loanId)
                        ->update([
                            'bca_payment_frequency_type_id' => $loanInfo['bca_payment_frequency_type_id'],
                            'bca_repayment_type_id' => $loanInfo['bca_repayment_type_id'],
                        ]);
                }



                foreach ($directors as $key => $director) {

                    $directorExists = DirectorInfo::where('id', $director['id'])
                        ->where('wp_business_info_id', $id)
                        ->first();

                    if ($directorExists) {
                        $directorExists->update([
                            'cifas_return' => $director['cifas_return'] ?? '',
                            'transunion' => $director['transunion'] ?? '',
                        ]);
                    }

                    if (isset($director['properties_and_other_assets']) && is_array($director['properties_and_other_assets'])) {
                        foreach ($director['properties_and_other_assets'] as $property) {
                            if (isset($property['zoopla_value'])) {
                                $test[] = [
                                    'zoopla_value' => $property['zoopla_value'],
                                    'mortgage' => $property['mortgage'],
                                    'equity' => $property['equity'],
                                    'id' => $property['id'],
                                    'diredi' => $director['id'],
                                ];
                                $existingRecord = DirectorPropertiesAndOtherAssets::where('id', $property['id'])
                                    ->where('wp_director_info_id', $director['id'])
                                    ->first();

                                if ($existingRecord) {
                                    $existingRecord->update([
                                        'zoopla_value' => $property['zoopla_value'],
                                        'mortgage' => $property['mortgage'],
                                        'equity' => $property['equity'],
                                    ]);
                                }
                            }
                        }
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Form submitted and data saved successfully!',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Error in getLoanData: ' . $e->getMessage(), [
                    'error_trace' => $e->getTraceAsString(),
                ]);

                return response()->json(['status' => false, 'message' => 'An error occurred while processing your request.', 'error_log', $e->getTraceAsString()], 500);
            }
        }
        return redirect()->route('home');
    }

    public function commiteePaperDoc(Request $request, $id, $loanId)
    {
        $business_info = BusinessInfo::find($id);

        if (!$business_info) {
            return redirect()->route('home')->withErrors('Business info not found.');
        }

        $loanInfo = DB::table('wp_loan_info')->find($loanId);

        if (!$loanInfo) {
            return redirect()->route('home')->withErrors('Loan information not found.');
        }

        try {
            $document = DocumentService::createCommiteePaperDoc($id, $loanId);
            return $document;
        } catch (\Exception $e) {
            Log::error('Error creating committee paper document: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error creating document.');
        }
    }



    // public function commiteePaperPdf(Request $request, $id, $loanId)
    // {
    //     if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
    //         return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
    //     }

    //     if (empty($id && $loanId)) {
    //         return redirect()->route('home');
    //     }

    //     $business_info = BusinessInfo::where('id', $id)->first();
    //     $loanInfo = DB::table('wp_loan_info')->where('id', $loanId)->first();

    //     if (empty($business_info)) {
    //         return redirect()->route('home');
    //     }

    //     $response = [];

    //     $response['businessInfo'] = $business_info;
    //     $response['loanInfo'] = $loanInfo ?: [];
    //     if ($loanInfo) {
    //         $response['loanInfo'] = $loanInfo ?: [];
    //         $response['wpIntroducersInfo'] = DB::table('wp_introducers_info')->where('id', $loanInfo->broker_id)->first() ?: [];
    //         $response['bcaPaymentFrequencyTypes'] = DB::table('bca_payment_frequency_types')->where('id', $loanInfo->bca_payment_frequency_type_id)->first() ?: [];
    //         $response['bcaRePaymentTypes'] = DB::table('bca_repayment_types')->where('id', $loanInfo->bca_repayment_type_id)->first() ?: [];
    //     }
    //     $response['gaCreditSafe'] = DB::table('ga_credit_safe')->where('id', $business_info->ga_credit_safe_id)->first() ?: [];
    //     $response['gaCreditSafeShareHolders'] = DB::table('ga_credit_safe_share_holders')->where('ga_credit_safe_id', $business_info->ga_credit_safe_id)->get()->toArray();
    //     $response['directors'] = DB::table('wp_director_info')->where('wp_business_info_id', $id)->get()->toArray() ?: [];
    //     $response['priceModelData'] = PriceModel::where('wp_business_info_id', $id)->first() ?: [];
    //     $response['gaCreditSafeCountyCourtJudgements'] = DB::table('ga_credit_safe_county_court_judgements')->where('ga_credit_safe_id', $business_info->ga_credit_safe_id)->get();

    //     $html = view('businesses.pdf-committe-paper', ['response' => $response])->render();

    //     $mpdf = new Mpdf();
    //     $mpdf->WriteHTML($html);
    //     return $mpdf->Output('download.pdf', 'D');
    // }

    public function test(Request $request)
    {

        $loanId = 1465; //1517 or 1465
        $id = 1338;
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id && $loanId)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();
        $loanInfo = DB::table('wp_loan_info')->where('id', $loanId)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }
    }

    public function fundingChecklist(Request $request, $id)
    {
        if (!in_array(strtoupper($request->method()), ['GET'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        // Define default items
        $defaultChecklistItems = [
            ['folder_to_save' => 'Pre-Approval', 'checklist' => 'Signed AIP'],
            ['folder_to_save' => 'Pre-Approval', 'checklist' => 'AIP Further information satisfied and signed off by team'],
            ['folder_to_save' => 'Pre-Approval', 'checklist' => '(if property ownership) Add to team email - SOP value against online valuation)'],
            ['folder_to_save' => 'Pre-Funding', 'checklist' => 'Refi Metric if applicable? (only applicable for refinancing customer)'],
            ['folder_to_save' => 'n/a', 'checklist' => 'Open Banking connected? (All company accounts)'],
            ['folder_to_save' => 'n/a', 'checklist' => 'Post Obanking - check for recent finance stacking - save cashflow to pre-funding folder'],
            ['folder_to_save' => 'n/a', 'checklist' => 'Complete and add the Committee paper notes on the website'],
            ['folder_to_save' => 'n/a', 'checklist' => 'Check for any newly registered Charges on the Companies House (looking for any not mentioned in the paper)'],
            ['folder_to_save' => 'n/a', 'checklist' => 'Add Open Banking to debtors table with correct details'],
            ['folder_to_save' => 'Identificationn', 'checklist' => 'Statement of Positions signed and witnessed (For each director)'],
            ['folder_to_save' => 'Identification', 'checklist' => 'Complete a CIFAS check on each director w/saved snip'],
            ['folder_to_save' => 'Identification', 'checklist' => 'AML\'s completed and saved (For each director) Check for issues PASSPORT ONLY DOCUMENT)'],
            ['folder_to_save' => 'Identification', 'checklist' => 'TransUnion Report saved down (needs to include mortgage details for all properties involved unles it’s a business).'],
            ['folder_to_save' => 'Property Information', 'checklist' => 'Land Registration on EACH director\'s owned property (saved)'],
            ['folder_to_save' => 'Property Information', 'checklist' => 'Property valuation w/saved snip (via Zoopla/On the move etc)'],
        ];

        $defaultAgreementItems = [
            ['folder_to_save' => 'Legals', 'agreement_to_send_out' => 'Debenture signatures (save two versions down)'],
            ['folder_to_save' => 'Legals', 'agreement_to_send_out' => 'Save a copy of the debenture without the signature pages (use PDF24 and print pages 1-10 or 1-11) and save in format Debenture_Agreement_[company number]_[debenture date] in the legals folder'],
            ['folder_to_save' => 'Legals', 'agreement_to_send_out' => 'Legal Charge / Equitable Charge saved down sign off.'],
            ['folder_to_save' => 'Legals', 'agreement_to_send_out' => 'Funding Agreement'],
            ['folder_to_save' => 'n/a', 'agreement_to_send_out' => 'Post Obanking - check for recent finance stacking - save cashflow to pre-funding folder'],
            ['folder_to_save' => 'Phone Calls', 'agreement_to_send_out' => 'Complete the Pre-funding call with DIRECTOR and instruct Warren to save down'],
            ['folder_to_save' => 'Phone Calls', 'agreement_to_send_out' => 'Call other PG providers and ask if they understood they have a provided a PG anf if they would like to go through the prefunding call with you'],
            ['folder_to_save' => 'Phone Calls', 'agreement_to_send_out' => 'Instruct management to make payment (include FCAXXX, bank account details + amount)'],
        ];

        $defaultOnceFundedItems = [
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Check entity on Creditsafe is being Monitored.'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Move prospects folder to deals folder'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add Correct information to the debtors table as LIVE deal (Access)'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add the deal to Laravel / LMS'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add to front end of website (Loan info)'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add date to BCA book debenture section - EMAIL WARREN TO REGISTER'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add debenture details to CIMS'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add email reminder for 10-days to check debenture has been filed (add underwriters/line manager)'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add real number/email/website & switchboard number (via phpMyAdmin)'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Create new tab in BCA book for new entity (FCAXXX)'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Returning deal: broker is 70%'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'New deal: broker is 100%'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Add deal collections tab in BCA book (see collections procedures)'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Pass over to Josh via the Handover Group'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Committee Paper has been amended to reflect all gathered info (Including updated funded amount with metrics, taxes, investigate lines. Anything that was received post the credit committee decision). This needs to be ready so the line manager can send out an updated final paper with the funded deal email in the following step.'],
            ['folder_to_save' => 'n/a', 'to_complete_once_funded' => 'Team Leader has sent out Funded email to team with credit paper attached '],

        ];

        // Fetch existing checklist items for this business
        $existingChecklistItems = PreSendingOutTheAgreement::where('wp_business_info_id', $id)
            ->orderBy('order_index')
            ->get()
            ->keyBy('order_index')
            ->toArray();

        $existingAgreementItems = AgreementToSendOut::where('wp_business_info_id', $id)
            ->orderBy('order_index')
            ->get()
            ->keyBy('order_index')
            ->toArray();

        $existingOnceFundedItems = CompleteOnceFunded::where('wp_business_info_id', $id)
            ->orderBy('order_index')
            ->get()
            ->keyBy('order_index')
            ->toArray();

        // Merge existing items with default items
        $checklistItems = collect($defaultChecklistItems)->map(function ($item, $index) use ($existingChecklistItems) {
            return array_merge($item, $existingChecklistItems[$index] ?? []);
        });

        $agreementItems = collect($defaultAgreementItems)->map(function ($item, $index) use ($existingAgreementItems) {
            return array_merge($item, $existingAgreementItems[$index] ?? []);
        });

        $onceFundedItems = collect($defaultOnceFundedItems)->map(function ($item, $index) use ($existingOnceFundedItems) {
            return array_merge($item, $existingOnceFundedItems[$index] ?? []);
        });

        $isNewChecklist = empty($existingChecklistItems);
        $isNewAgreement = empty($existingAgreementItems);
        $isNewOnceFunded = empty($existingOnceFundedItems);

        // return $checklistItems;
        return view('businesses.add.funding-checklist')->with([
            'id' => $id,
            'checklistItems' => $checklistItems,
            'agreementItems' => $agreementItems,
            'onceFundedItems' => $onceFundedItems,
            'isNewChecklist' => $isNewChecklist,
            'isNewAgreement' => $isNewAgreement,
            'isNewOnceFunded' => $isNewOnceFunded,
        ]);
    }

    public function saveFundingChecklist(Request $request, $id)
    {
        // return $request;
        if (!in_array(strtoupper($request->method()), ['POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home')->with('error_message', 'Invalid business ID.');
        }

        try {
            DB::transaction(function () use ($request, $id) {
                $checklistItems = $request->input('checklist_items', []);
                $agreementItems = $request->input('agreement_items', []);
                $onceFundedItems = $request->input('once_funded_items', []);

                $this->processItems(PreSendingOutTheAgreement::class, $checklistItems, $id, [
                    'checklist'
                ]);
                $this->processItems(AgreementToSendOut::class, $agreementItems, $id, [
                    'agreement_to_send_out'
                ]);
                $this->processItems(CompleteOnceFunded::class, $onceFundedItems, $id, [
                    'to_complete_once_funded'
                ]);
            });

            if ($request->input('download_doc') == 1) {
                return $this->downloadFundingChecklist($id); // Call your download logic here
            }

            return redirect()->back()->with('message', 'Funding checklist and agreements saved successfully.');
        } catch (\Exception $e) {
            dd($e);
            Log::error('Error saving funding checklist and agreements: ' . $e->getMessage());
            return redirect()->back()->with('error_message', 'An error occurred while saving. Please try again.');
        }
    }

    private function processItems($modelClass, $items, $id, $customFields = [])
    {
        foreach ($items as $index => $item) {
            $existingRecord = $modelClass::where('wp_business_info_id', $id)
                ->where('order_index', $index)
                ->first();

            $updateData = [
                'folder_to_save' => $item['folder_to_save'] ?? '',
                'completed' => $item['completed'] ?? 'No',
                'second_checker_completed' => $item['second_checker_completed'] ?? 'No',
                'notes' => $item['notes'] ?? '',
            ];

            foreach ($customFields as $field) {
                $updateData[$field] = $item[$field] ?? '';
            }

            if ((is_null($existingRecord) && $item['completed'] !== 'No') || ($existingRecord && $existingRecord->completed !== ($item['completed'] ?? 'No'))) {
                $updateData['logged_user_id_first_checker'] = Auth::user()->ID;
                $updateData['updated_at_first_checker'] = date('Y-m-d H:i:s');
            }


            if ((is_null($existingRecord) && $item['second_checker_completed'] !== 'No') || ($existingRecord && $existingRecord->second_checker_completed !== ($item['second_checker_completed'] ?? 'No'))) {
                $updateData['logged_user_id_second_checker'] = Auth::user()->ID;
                $updateData['updated_at_second_checker'] = date('Y-m-d H:i:s');
            }


            $modelClass::updateOrCreate(
                [
                    'wp_business_info_id' => $id,
                    'order_index' => $index,
                ],
                $updateData
            );
        }
    }

    // public function downloadFundingChecklist($id)
    // {
    //     if (empty($id)) {
    //         return redirect()->route('home');
    //     }

    //     $business_info = BusinessInfo::where('id', $id)->first();

    //     if (empty($business_info)) {
    //         return redirect()->route('home');
    //     }

    //     // Fetch existing checklist items for this business
    //     $existingChecklistItems = PreSendingOutTheAgreement::where('wp_business_info_id', $id)->get()->toArray();
    //     $existingAgreementItems = AgreementToSendOut::where('wp_business_info_id', $id)->get()->toArray();
    //     $existingOnceFundedItems = CompleteOnceFunded::where('wp_business_info_id', $id)->get()->toArray();

    //     $phpWord = new PhpWord();
    //     $section = $phpWord->addSection();

    //     $tableStyle = [
    //         'borderSize' => 6,
    //         'borderColor' => '000000',
    //         'cellMarginTop' => 150,
    //         'cellMarginBottom' => 150,
    //         'cellMarginLeft' => 150,
    //         'cellMarginRight' => 150
    //     ];

    //     $phpWord->addTableStyle('ChecklistTable', $tableStyle);

    //     $headerCellStyle = [
    //         'bgColor' => '1F497D',
    //         'alignment' => 'center'
    //     ];

    //     $headerTextStyle = [
    //         'bold' => true,
    //         'color' => 'FFFFFF',
    //     ];

    //     $paragraphStyle = [
    //         'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
    //     ];

    //     $noCellStyle = [
    //         'bgColor' => 'FFC7CE',
    //         'alignment' => 'center',
    //         'color' => '000000',
    //     ];

    //     $yesCellStyle = [
    //         'bgColor' => 'C6EFCE',
    //         'alignment' => 'center',
    //         'color' => '000000'
    //     ];

    //     $table = $section->addTable('ChecklistTable');

    //     $table->addRow();
    //     $cell = $table->addCell(2000, ['gridSpan' => 3] + $headerCellStyle);
    //     $cell->addText('Pre-Sending Out The Agreement Checklist', $headerTextStyle, $paragraphStyle);

    //     $cell = $table->addCell(2000, ['gridSpan' => 2] + $headerCellStyle);
    //     $cell->addText('Second Checker (Other Underwriter)', $headerTextStyle, $paragraphStyle);

    //     if (count($existingChecklistItems) > 0) {
    //         $table->addRow();
    //         $table->addCell(2000, $headerCellStyle)->addText('Folder to save', $headerTextStyle);
    //         $table->addCell(5000, $headerCellStyle)->addText('Checklist', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Completed', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Completed', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Notes', $headerTextStyle);

    //         foreach ($existingChecklistItems as $row) {
    //             $table->addRow();

    //             $table->addCell(2000)->addText($row['folder_to_save']);
    //             $table->addCell(5000)->addText($row['checklist']);

    //             // Add first "Completed" status with conditional styling
    //             if ($row['completed'] == 'Yes') {
    //                 $table->addCell(2000, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
    //             } else {
    //                 $table->addCell(2000, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
    //             }

    //             // Add second "Completed" status with conditional styling
    //             if ($row['second_checker_completed'] == 'Yes') {
    //                 $table->addCell(2000, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
    //             } else {
    //                 $table->addCell(2000, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
    //             }

    //             $table->addCell(2000)->addText($row['notes']);
    //         }
    //     }

    //     if (count($existingAgreementItems) > 0) {
    //         $table->addRow();
    //         $table->addCell(2000, $headerCellStyle)->addText('Folder to save', $headerTextStyle);
    //         $table->addCell(5000, $headerCellStyle)->addText('Agreement To Send Out', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Completed', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Completed', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Notes', $headerTextStyle);

    //         foreach ($existingAgreementItems as $row) {
    //             $table->addRow();

    //             $table->addCell(2000)->addText($row['folder_to_save']);
    //             $table->addCell(5000)->addText($row['agreement_to_send_out']);

    //             // Add first "Completed" status with conditional styling
    //             if ($row['completed'] == 'Yes') {
    //                 $table->addCell(2000, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
    //             } else {
    //                 $table->addCell(2000, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
    //             }

    //             // Add second "Completed" status with conditional styling
    //             if ($row['second_checker_completed'] == 'Yes') {
    //                 $table->addCell(2000, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
    //             } else {
    //                 $table->addCell(2000, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
    //             }

    //             $table->addCell(2000)->addText($row['notes']);
    //         }
    //     }

    //     if (count($existingOnceFundedItems) > 0) {

    //         $table->addRow();
    //         $table->addCell(2000, $headerCellStyle)->addText('Folder to save', $headerTextStyle);
    //         $table->addCell(5000, $headerCellStyle)->addText('To complete Once funded', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Completed', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Completed', $headerTextStyle);
    //         $table->addCell(2000, $headerCellStyle)->addText('Notes', $headerTextStyle);

    //         foreach ($existingOnceFundedItems as $row) {
    //             $table->addRow();

    //             $table->addCell(2000)->addText($row['folder_to_save']);

    //             $sanitizedText = htmlspecialchars($row['to_complete_once_funded'], ENT_QUOTES | ENT_XML1, 'UTF-8');
    //             $table->addCell(5000)->addText($sanitizedText);

    //             // Add first "Completed" status with conditional styling
    //             if ($row['completed'] == 'Yes') {
    //                 $table->addCell(2000, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
    //             } else {
    //                 $table->addCell(2000, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
    //             }

    //             // Add second "Completed" status with conditional styling
    //             if ($row['second_checker_completed'] == 'Yes') {
    //                 $table->addCell(2000, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
    //             } else {
    //                 $table->addCell(2000, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
    //             }

    //             $table->addCell(2000)->addText($row['notes']);
    //         }
    //     }

    //     // Save the document
    //     $fileName = 'Checklist.docx';
    //     $tempFile = tempnam(sys_get_temp_dir(), $fileName);

    //     // // Set the writer to use PclZip instead of ZipArchive
    //     // \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);

    //     $phpWordWriter = IOFactory::createWriter($phpWord, 'Word2007');
    //     $phpWordWriter->save($tempFile);

    //     return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    // }

    public function downloadFundingChecklist($id)
    {
        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        try {
            $document = DocumentService::createFundingChecklistDoc($id);
            return $document;
        } catch (\Exception $e) {
            Log::error('Error creating committee paper document: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error creating document.');
        }
    }

    // for SIF Funding checklist
    public function sifFundingChecklist(Request $request, $id)
    {
        if (!in_array(strtoupper($request->method()), ['GET'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        // Define default items
        $defaultInvoiceItems = [
            ['invoice' => 'Is the invoice correctly made out in the name of the customer(s) (Debtors) which match the Credit Insurance Limit(s) on file and also match exactly the Name on any PO.'],
            ['invoice' => 'Where the client has their own Credit Insurance policy the limit(s) should be confirmed via the Insurers online portal using the client’s log-in.'],
            ['invoice' => 'Each debtor invoice(s) total must fall within an approved Credit Insured Limit or where Credit Insurance does not apply within a credit committee approved recourse limit. Where a discretionary Credit Insured limit or Recourse Limit is to be used it is essential that the credit information used to establish the limit is in-date with no recent adverse changes.'],
            ['invoice' => 'Does the invoice value fall in range of the average invoice value funded for this client.'],
            ['invoice' => 'Invoices with a value of £10,000 and above must be accompanied by a copy of the debtor’s purchase order or contract of supply. Terms of these items should be read and approved by an authorised client team member.'],
            ['invoice' => 'Does the invoice need a proof of delivery or other form of proof of debt in accordance with the client’s facility requirements.'],
            ['invoice' => 'Are terms of payment shown.'],
            ['invoice' => 'Are invoices past the due date.'],
            ['invoice' => 'Does the actual shipment date match the stated delivery date on the PO.'],
            ['invoice' => 'Does the work performed/goods delivered appear to match the client’s usual business.'],
            ['invoice' => 'Does the debtor have any overdue invoices (or credit notes) with us on this client or other clients.'],
            ['invoice' => 'Does the Debtor have any invoices in dispute with this client or any other client.'],
            ['invoice' => 'Does the invoice appear to relate to part shipments or stage payments – if so is that part of the client’s “normal business activity”.'],
            ['invoice' => 'Does the invoice add up correctly.'],
            ['invoice' => 'Is VAT shown.'],
            ['invoice' => 'Is the assignment clause clearly and correctly shown.'],
            ['invoice' => 'Is there a GNA (General Notice of Assignment) on file.'],
            ['invoice' => 'Where there is no GNA held this should be obtained   simultaneously with verification using Email.'],
        ];

        $defaultClientItems = [
            ['client' => 'The client account should be checked to ensure that the client is compliant with regards to all ongoing operational information i.e. management accounts, End of Year accounts, HMRC (especially TTP’s), bank statements and that the client account is NOT on hold due to disputes or information not supplied on time.'],
            ['client' => 'The client account should be checked to identify if there  are any overdue invoices or outstanding credit notes with other debtors and if these are of material concern.'],
        ];

        $defaultVerificationAndCompletionItems = [
            ['verification_and_completion' => 'Each invoice shall be verified with the debtor by ops team using email, and/or by telephone call.'],
            ['verification_and_completion' => 'Advances against client invoice schedules should have two authorised team members signatures in accordance with the individual underwriting limits agreed by credit committee from time to time.'],
            ['verification_and_completion' => 'As soon as the verification process is complete and transaction checklist completed the advance may be made to the client’s nominated bank account in accordance with the terms for that client.'],
        ];

        // Fetch existing checklist items for this business
        $existingInvoicetItems = SifInvoice::where('wp_business_info_id', $id)
            ->orderBy('order_index')
            ->get()
            ->keyBy('order_index')
            ->toArray();

        $existingClientItems = SifClient::where('wp_business_info_id', $id)
            ->orderBy('order_index')
            ->get()
            ->keyBy('order_index')
            ->toArray();

        $existingVerificationAndCompletionItems = SifVerificationAndCompletion::where('wp_business_info_id', $id)
            ->orderBy('order_index')
            ->get()
            ->keyBy('order_index')
            ->toArray();

        // Merge existing items with default items
        $invoicetItems = collect($defaultInvoiceItems)->map(function ($item, $index) use ($existingInvoicetItems) {
            return array_merge($item, $existingInvoicetItems[$index] ?? []);
        });

        $clientItems = collect($defaultClientItems)->map(function ($item, $index) use ($existingClientItems) {
            return array_merge($item, $existingClientItems[$index] ?? []);
        });

        $verificationAndCompletion = collect($defaultVerificationAndCompletionItems)->map(function ($item, $index) use ($existingVerificationAndCompletionItems) {
            return array_merge($item, $existingVerificationAndCompletionItems[$index] ?? []);
        });

        $isNewChecklist = empty($existingInvoicetItems);
        $isNewAgreement = empty($existingClientItems);
        $isNewOnceFunded = empty($existingVerificationAndCompletionItems);

        return view('businesses.add.sif-funding-checklist')->with([
            'id' => $id,
            'invoiceItems' => $invoicetItems,
            'clientItems' => $clientItems,
            'verificationAndCompletion' => $verificationAndCompletion,
            'isNewChecklist' => $isNewChecklist,
            'isNewAgreement' => $isNewAgreement,
            'isNewOnceFunded' => $isNewOnceFunded,
        ]);
    }

    public function saveSifFundingChecklist(Request $request, $id)
    {
        // return $request;
        if (!in_array(strtoupper($request->method()), ['POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home')->with('error_message', 'Invalid business ID.');
        }

        try {
            DB::transaction(function () use ($request, $id) {
                $invoiceItems = $request->input('invoice_items', []);
                $clientItems = $request->input('client_items', []);
                $verificationAndCompletionItems = $request->input('verification_and_completion_items', []);

                $this->processSifItems(SifInvoice::class, $invoiceItems, $id, [
                    'invoice'
                ]);
                $this->processSifItems(SifClient::class, $clientItems, $id, [
                    'client'
                ]);
                $this->processSifItems(SifVerificationAndCompletion::class, $verificationAndCompletionItems, $id, [
                    'verification_and_completion'
                ]);
            });

            if ($request->input('download_doc') == 1) {
                return $this->downloadSifFundingChecklist($id);
            }

            return redirect()->back()->with('message', 'Data saved successfully.');
        } catch (\Exception $e) {
            Log::error('Error saving Data: ' . $e->getMessage());
            return redirect()->back()->with('error_message', 'An error occurred while saving. Please try again.');
        }
    }

    private function processSifItems($modelClass, $items, $id, $customFields = [])
    {
        foreach ($items as $index => $item) {
            $existingRecord = $modelClass::where('wp_business_info_id', $id)
                ->where('order_index', $index)
                ->first();

            $updateData = [
                'completed' => $item['completed'] ?? 'No',
                'second_checker_completed' => $item['second_checker_completed'] ?? 'No',
                'notes' => $item['notes'] ?? '',
            ];

            foreach ($customFields as $field) {
                $updateData[$field] = $item[$field] ?? '';
            }

            if ((is_null($existingRecord) && $item['completed'] !== 'No') || ($existingRecord && $existingRecord->completed !== ($item['completed'] ?? 'No'))) {
                $updateData['logged_user_id_first_checker'] = Auth::user()->ID;
                $updateData['updated_at_first_checker'] = date('Y-m-d H:i:s');
            }


            if ((is_null($existingRecord) && $item['second_checker_completed'] !== 'No') || ($existingRecord && $existingRecord->second_checker_completed !== ($item['second_checker_completed'] ?? 'No'))) {
                $updateData['logged_user_id_second_checker'] = Auth::user()->ID;
                $updateData['updated_at_second_checker'] = date('Y-m-d H:i:s');
            }


            $modelClass::updateOrCreate(
                [
                    'wp_business_info_id' => $id,
                    'order_index' => $index,
                ],
                $updateData
            );
        }
    }

    public function downloadSifFundingChecklist($id)
    {
        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        try {
            $document = DocumentService::createSifFundingChecklistDoc($id);
            return $document;
        } catch (\Exception $e) {
            Log::error('Error creating committee paper document: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error creating document.');
        }
    }

    public function aip(Request $request, $id)
    {
        if (!in_array(strtoupper($request->method()), ['GET', 'POST'])) {
            return redirect()->back()->withInput()->with('error_message', 'Invalid operation.');
        }

        if (empty($id)) {
            return redirect()->route('home');
        }

        $business_info = BusinessInfo::where('id', $id)->first();

        if (empty($business_info)) {
            return redirect()->route('home');
        }

        $price_model = PriceModel::where('wp_business_info_id', $id)->first();
        $customerInfo = CustomerInfo::where('reg_number', $business_info->registration_number)->first();
        $lender = Lender::where('wp_business_info_id', $id)->get() ?? [];

        if ($request->method() == 'GET') {
            return view('businesses.add.aip')->with([
                'id' => $id,
                'business_detail' => $business_info,
                'price_model' => $price_model,
                'customerInfo' => $customerInfo,
                'lender' => $lender
            ]);
        }

        if ($request->method() == 'POST') {

            $rules = [];
            $attributes = [];

            $fieldMappings = [
                'lender' => [
                    'key' => 'lender.*.lender_name',
                    'rule' => 'required',
                    'attribute' => 'Lender Name',
                ],
            ];

            foreach ($fieldMappings as $inputKey => $mapping) {
                $rules[$mapping['key']] = $mapping['rule'];
                $attributes[$mapping['key']] = $mapping['attribute'];
            }

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($attributes);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            try {

                $lenders = $request->input('lender');
                if (isset($lenders) && count($lenders) > 0) {
                    $lenders = $request->input('lender');
                    Lender::where('wp_business_info_id', $id)->delete();

                    DB::transaction(function () use ($request, $id, $lenders) {
                        foreach ($lenders as $lenderData) {

                            $data = [
                                'wp_business_info_id' => $id,
                                'lender_name' => $lenderData['lender_name'],
                                'outstanding_amount' => (float) str_replace('£', '', $lenderData['outstanding_amount']),
                                'how_much_gets_paid' => (float) str_replace('£', '', $lenderData['how_much_gets_paid']),
                                'how_often_it_is_paid' => $lenderData['how_often_it_is_paid'],
                                'expected_maturity' => $lenderData['expected_maturity'],
                                'security_given' => $lenderData['security_given'],
                            ];

                            Lender::create($data);
                        }
                    });
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved successfully',
                ]);
            } catch (\Exception $e) {
                // dd($e);
                Log::error('Error creating committee paper document: ' . $e->getMessage());
                return response()->json(['error_message' => 'Error creating document.', 'error-log' => $e->getMessage()], 400);
            }
        }
    }

    public function downloadAIP(Request $request, $id)
    {
        $inputData = $request->all();

        try {
            $document = DocumentService::createAIPDoc($inputData, $id);
            return $document;
        } catch (\Exception $e) {
            // die($e);
            Log::error('Error creating committee paper document: ' . $e->getMessage());
            return redirect()->back()->with(['error_message' => 'Error creating document.']);
        }
    }

    public function migratefile()
    {
        // Check if the tables already exist to avoid duplication
        if (!Schema::hasTable('wp_director_assets')) {
            Schema::create('wp_director_assets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('wp_director_info_id');
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

        // Add more tables following the same pattern
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

                if (Schema::hasColumn('wp_director_info', 'address_street')) {
                    $table->renameColumn('address_street', 'address_line_1');
                }

                if (Schema::hasColumn('wp_director_info', 'address_city')) {
                    $table->renameColumn('address_city', 'address_line_2');
                }

                if (!Schema::hasColumn('wp_director_info', 'house_number')) {
                    $table->string('house_number', 100)->nullable()->after('address_simple_value');
                }

                if (!Schema::hasColumn('wp_director_info', 'address_line_3')) {
                    $table->string('address_line_3', 256)->nullable();
                }

                if (!Schema::hasColumn('wp_director_info', 'cifas_return')) {
                    $table->string('cifas_return', 256)->nullable()->after('ni_number');
                }

                if (!Schema::hasColumn('wp_director_info', 'transunion')) {
                    $table->string('transunion', 256)->nullable()->after('ni_number');
                }

                if (!Schema::hasColumn('wp_director_info', 'sop')) {
                    $table->string('sop', 2048)->nullable()->after('ni_number');
                }

                if (!Schema::hasColumn('wp_director_info', 'next_of_kin_full_name')) {
                    $table->string('next_of_kin_full_name', 150)->nullable()->after('ni_number');
                }

                if (!Schema::hasColumn('wp_director_info', 'next_of_kin_mobile_number')) {
                    $table->string('next_of_kin_mobile_number', 20)->nullable()->after('ni_number');
                }

                if (!Schema::hasColumn('wp_director_info', 'next_of_kin_email_address')) {
                    $table->string('next_of_kin_email_address', 150)->nullable()->after('ni_number');
                }
            });
        }

        if (!Schema::hasTable('wp_price_model')) {
            Schema::create('wp_price_model', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('advance_requested', 100)->nullable();
                $table->string('repayment_period', 100)->nullable();
                $table->string('multiple', 100)->nullable();
                $table->string('property_equity', 100)->nullable();
                $table->string('average_monthly_revenue', 100)->nullable();
                $table->foreign('wp_business_info_id', 'wp_price_model_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                // $table->foreign('wp_loan_info_id', 'wp_loan_info_id_fk')
                //     ->references('id')
                //     ->on('wp_loan_info')
                //     ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('wp_pre_sending_out_the_agreements')) {
            Schema::create('wp_pre_sending_out_the_agreements', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('folder_to_save', 100)->nullable();
                $table->string('checklist', 100)->nullable();
                $table->string('completed', 100)->nullable();
                $table->string('second_checker_completed', 100)->nullable();
                $table->string('notes', 100)->nullable();
                $table->string('order_index', 100)->nullable();
                $table->string('logged_user_id_first_checker', 100)->nullable();
                $table->string('logged_user_id_second_checker', 100)->nullable();

                $table->foreign('wp_business_info_id', 'wp_pre_sending_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_agreement_to_send_outs')) {
            Schema::create('wp_agreement_to_send_outs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('folder_to_save', 100)->nullable();
                $table->string('agreement_to_send_out')->nullable();
                $table->string('completed', 100)->nullable();
                $table->string('second_checker_completed', 100)->nullable();
                $table->string('notes', 100)->nullable();
                $table->string('order_index', 100)->nullable();
                $table->string('logged_user_id_first_checker', 100)->nullable();
                $table->string('logged_user_id_second_checker', 100)->nullable();

                $table->foreign('wp_business_info_id', 'agreement_to_send_out_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_complete_once_funded')) {
            Schema::create('wp_complete_once_funded', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('folder_to_save', 100)->nullable();
                $table->string('to_complete_once_funded')->nullable();
                $table->string('completed', 100)->nullable();
                $table->string('second_checker_completed', 100)->nullable();
                $table->string('notes', 100)->nullable();
                $table->string('order_index', 100)->nullable();
                $table->string('logged_user_id_first_checker', 100)->nullable();
                $table->string('logged_user_id_second_checker', 100)->nullable();

                $table->foreign('wp_business_info_id', 'wp_complete_once_funded_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Check if the table exists, then update it
        if (Schema::hasTable('wp_price_model')) {
            Schema::table('wp_price_model', function (Blueprint $table) {
                // Adding new columns
                if (!Schema::hasColumn('wp_price_model', 'arrangement_fee_excl_VAT')) {
                    $table->integer('arrangement_fee_excl_VAT')->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'arrangement_fee_incl_VAT')) {
                    $table->integer('arrangement_fee_incl_VAT')->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'total_repayable')) {
                    $table->decimal('total_repayable', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'pre_introducer_IRR')) {
                    $table->decimal('pre_introducer_IRR', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'post_introducer_IRR')) {
                    $table->decimal('post_introducer_IRR', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'duration')) {
                    $table->integer('duration')->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'how_long_until_breakeven')) {
                    $table->integer('how_long_until_breakeven')->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'rate_of_income')) {
                    $table->decimal('rate_of_income', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'fixed_repayment_amount')) {
                    $table->decimal('fixed_repayment_amount', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'daily_repayment_amount')) {
                    $table->decimal('daily_repayment_amount', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'commission')) {
                    $table->decimal('commission', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('wp_price_model', 'refinance')) {
                    $table->string('refinance', 100)->nullable();
                }
            });
        }

        if (!Schema::hasTable('wp_committee_paper')) {
            Schema::create('wp_committee_paper', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->integer('wp_loan_info_id');
                $table->string('business_name', 100)->nullable();
                $table->string('introduction', 100)->nullable();
                $table->string('company_number', 100)->nullable();
                $table->string('amount_funding', 100)->nullable();
                $table->string('registered_address', 100)->nullable();
                $table->string('servicing_and_administration_fee', 100)->nullable();
                $table->string('date_of_incorporation', 100)->nullable();
                $table->string('multiple', 100)->nullable();
                $table->string('website', 100)->nullable();
                $table->string('total_repayable', 100)->nullable();
                $table->string('shareholding_and_directors', 100)->nullable();
                $table->string('allocation_of_the_funds', 100)->nullable();
                $table->string('personal_guarantees', 100)->nullable();
                $table->string('pre_introducer_irr', 100)->nullable();
                $table->string('post_introducer_irr', 100)->nullable();
                $table->string('how_they_make_their_money', 100)->nullable();
                $table->string('why_irr_chosen', 100)->nullable();
                $table->string('duration', 100)->nullable();
                $table->string('how_long_until_breakeven', 100)->nullable();
                $table->string('rate_of_income', 100)->nullable();
                $table->string('creditsafe_status', 100)->nullable();
                $table->string('repayment_frequency', 100)->nullable();
                $table->string('active_ccjs', 100)->nullable();
                $table->string('repayment_type', 100)->nullable();
                $table->string('weighted_scorecard', 100)->nullable();
                $table->string('fixed_repayment_amount', 100)->nullable();
                $table->integer('ga_credit_safe_id')->nullable();
                $table->foreign('wp_business_info_id', 'wp_business_info_id_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->foreign('wp_loan_info_id', 'wp_loan_info_id_fk')
                    ->references('id')
                    ->on('wp_loan_info')
                    ->onDelete('cascade');
            });
        }

        if (Schema::hasTable('wp_committee_paper_director_info')) {
            Schema::dropIfExists('wp_committee_paper_director_info');
        }

        if (!Schema::hasTable('wp_sif_invoice')) {
            Schema::create('wp_sif_invoice', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('invoice', 100)->nullable();
                $table->string('completed', 100)->nullable();
                $table->string('second_checker_completed', 100)->nullable();
                $table->string('notes', 100)->nullable();
                $table->string('order_index', 100)->nullable();
                $table->string('logged_user_id_first_checker', 100)->nullable();
                $table->string('logged_user_id_second_checker', 100)->nullable();
                $table->foreign('wp_business_info_id', 'wp_sif_invoice_business_info_id_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_sif_client')) {
            Schema::create('wp_sif_client', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('client')->nullable();
                $table->string('completed', 100)->nullable();
                $table->string('second_checker_completed', 100)->nullable();
                $table->string('notes', 100)->nullable();
                $table->string('order_index', 100)->nullable();
                $table->string('logged_user_id_first_checker', 100)->nullable();
                $table->string('logged_user_id_second_checker', 100)->nullable();
                $table->foreign('wp_business_info_id', 'wp_sif_client_business_info_id_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_sif_verification_and_completion')) {
            Schema::create('wp_sif_verification_and_completion', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('verification_and_completion')->nullable();
                $table->string('completed', 100)->nullable();
                $table->string('second_checker_completed', 100)->nullable();
                $table->string('notes', 100)->nullable();
                $table->string('order_index', 100)->nullable();
                $table->string('logged_user_id_first_checker', 100)->nullable();
                $table->string('logged_user_id_second_checker', 100)->nullable();
                $table->foreign('wp_business_info_id', 'wp_sif_verification_and_completion_business_info_id_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wp_statement_of_position')) {
            Schema::create('wp_statement_of_position', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('wp_director_info_id');
                $table->string('director_name')->nullable();
                $table->string('email')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('location')->nullable();
                $table->string('document_name')->nullable();
                $table->string('submitted_by')->nullable();
                $table->foreign('wp_director_info_id', 'wp_statement_of_position_directorId_fk')
                    ->references('id')
                    ->on('wp_director_info')
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('wp_lender')) {
            Schema::create('wp_lender', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wp_business_info_id');
                $table->string('lender_name')->nullable();
                $table->decimal('outstanding_amount', 10, 2)->nullable();
                $table->decimal('how_much_gets_paid', 10, 2)->nullable();
                $table->string('how_often_it_is_paid')->nullable();
                $table->string('expected_maturity')->nullable();
                $table->string('security_given')->nullable();

                $table->foreign('wp_business_info_id', 'wp_lender_business_info_fk')
                    ->references('id')
                    ->on('wp_business_info')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('wp_director_properties_and_other_assets')) {
            Schema::table('wp_director_properties_and_other_assets', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_director_properties_and_other_assets', 'zoopla_value')) {
                    $table->text('zoopla_value')->nullable()->after('income');
                }

                if (!Schema::hasColumn('wp_director_properties_and_other_assets', 'mortgage')) {
                    $table->text('mortgage')->nullable()->after('income');
                }

                if (!Schema::hasColumn('wp_director_properties_and_other_assets', 'equity')) {
                    $table->text('equity')->nullable()->after('income');
                }
            });
        }

        if (Schema::hasTable('wp_pre_sending_out_the_agreements')) {
            Schema::table('wp_pre_sending_out_the_agreements', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_pre_sending_out_the_agreements', 'updated_at_first_checker')) {
                    $table->timestamp('updated_at_first_checker')->nullable();
                }
                if (!Schema::hasColumn('wp_pre_sending_out_the_agreements', 'updated_at_second_checker')) {
                    $table->timestamp('updated_at_second_checker')->nullable();
                }
            });
        }

        if (Schema::hasTable('wp_agreement_to_send_outs')) {
            Schema::table('wp_agreement_to_send_outs', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_agreement_to_send_outs', 'updated_at_first_checker')) {
                    $table->timestamp('updated_at_first_checker')->nullable();
                }
                if (!Schema::hasColumn('wp_agreement_to_send_outs', 'updated_at_second_checker')) {
                    $table->timestamp('updated_at_second_checker')->nullable();
                }
            });
        }

        if (Schema::hasTable('wp_complete_once_funded')) {
            Schema::table('wp_complete_once_funded', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_complete_once_funded', 'updated_at_first_checker')) {
                    $table->timestamp('updated_at_first_checker')->nullable();
                }
                if (!Schema::hasColumn('wp_complete_once_funded', 'updated_at_second_checker')) {
                    $table->timestamp('updated_at_second_checker')->nullable();
                }
            });
        }

        if (Schema::hasTable('wp_sif_invoice')) {
            Schema::table('wp_sif_invoice', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_sif_invoice', 'updated_at_first_checker')) {
                    $table->timestamp('updated_at_first_checker')->nullable();
                }
                if (!Schema::hasColumn('wp_sif_invoice', 'updated_at_second_checker')) {
                    $table->timestamp('updated_at_second_checker')->nullable();
                }
            });
        }

        if (Schema::hasTable('wp_sif_client')) {
            Schema::table('wp_sif_client', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_sif_client', 'updated_at_first_checker')) {
                    $table->timestamp('updated_at_first_checker')->nullable();
                }
                if (!Schema::hasColumn('wp_sif_client', 'updated_at_second_checker')) {
                    $table->timestamp('updated_at_second_checker')->nullable();
                }
            });
        }

        if (Schema::hasTable('wp_sif_verification_and_completion')) {
            Schema::table('wp_sif_verification_and_completion', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_sif_verification_and_completion', 'updated_at_first_checker')) {
                    $table->timestamp('updated_at_first_checker')->nullable();
                }
                if (!Schema::hasColumn('wp_sif_verification_and_completion', 'updated_at_second_checker')) {
                    $table->timestamp('updated_at_second_checker')->nullable();
                }
            });
        }

        if (Schema::hasTable('wp_business_info')) {
            Schema::table('wp_business_info', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_business_info', 'trcounty')) {
                    $table->string('trcounty', 100)->nullable()->after('trpostal_code');
                }
                if (!Schema::hasColumn('wp_business_info', 'county')) {
                    $table->string('county', 100)->nullable()->after('country');
                }
            });
        }

        if (Schema::hasTable('wp_loan_info')) {
            Schema::table('wp_loan_info', function (Blueprint $table) {
                if (!Schema::hasColumn('wp_loan_info', 'payment_weekday')) {
                    $table->string('payment_weekday', 100)->nullable();
                }
                if (!Schema::hasColumn('wp_loan_info', 'description')) {
                    $table->string('description', 256)->nullable();
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
            $bcaMilestoneStages = DB::table('wp_BCA_milestone_stages')->get()->toArray() ?? [];
            $sifMilestoneStages = DB::table('wp_SIF_milestone_stages')->get()->toArray() ?? [];
            $bcaSubStages = DB::table('wp_BCA_milestone_sub_stages')->get()->toArray() ?? [];
            $sifSubStages = DB::table('wp_SIF_milestone_sub_stages')->get()->toArray() ?? [];
            return view('businesses.add.loan-info.add')->with(compact('data', 'id', 'bcaMilestoneStages', 'sifMilestoneStages', 'bcaSubStages', 'sifSubStages'));
        }

        $validations = [
            'loan_number' => 'required',
            'loan_type' => 'required',
            'milestone' => 'required',
        ];

        if ($data) {
            // $validations['deal_status'] = 'required';
            // $validations['bca_payment_frequency_type_id'] = 'required';
            // $validations['debenture'] = 'required';
            // $validations['payment_weekday'] = 'required';
            // $validations['loan_status_message'] = 'required';
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
        }

        // return $request;
        $data->loan_number = $request->loan_number;
        $data->loan_type = $request->loan_type ?? '';
        $data->milestone = $request->milestone ?? '';
        $data->sub_milestone = $request->sub_milestone ?? '';
        $data->advance_requested = $request->advance_requested ?? '';
        $data->loan_purpose = $request->loan_purpose ?? '';
        $data->original_default_balance = $request->original_default_balance ?? '';
        $data->minimum_daily_repayment = $request->minimum_daily_repayment ?? '';
        $data->revenue_repayment_rate = !empty($request->revenue_repayment_rate) ? $request->revenue_repayment_rate : '';
        $data->broker_id = $request->broker_id ?? '';
        $data->deal_status = $request->deal_status ?? '';
        $data->bca_payment_frequency_type_id = !empty($request->bca_payment_frequency_type_id) ? $request->bca_payment_frequency_type_id : '';
        $data->analyst = $request->analyst ?? '';
        $data->live = !empty($request->live) ? $request->live : '';
        $data->CollectionDay = $request->CollectionDay ?? '';
        $data->outstanding_balance = $request->outstanding_balance ?? '';
        $data->status_code = $request->status_code ?? '';
        $data->debenture = $request->debenture ?? '';
        $data->funded_date = $request->funded_date ?? '';
        $data->bca_repayment_type_id = $request->bca_repayment_type_id ?? '';
        $data->payment_weekday = $request->payment_weekday ?? '';
        $data->loan_status_message = $request->loan_status_message ?? '';

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
