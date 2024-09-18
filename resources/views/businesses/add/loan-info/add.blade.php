@extends('index')
@section('title', Helper::getSiteTitle('Loan Info'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Loan Info</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Loan Info' }}</h4>
        </div>
    </div>
</div>
<form action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-12 mb-2'])
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(!empty($id))
                        @include('businesses.add.nav', ['id' => $id])
                    @endif
                    <div class="row">
                        <div class="card no-lt-rt-pad">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Loan Details</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'loan_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Loan Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (isset($data) && !empty($data))
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'loan_type'; @endphp
                                                <label class="form-label" for="{{ $index }}">Loan Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'advance_requested'; @endphp
                                            <label class="form-label" for="{{ $index }}">Advance Requested </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'loan_purpose';
                                                $list = ['Working Capital', 'Stock', 'Additional Staff', 'New Equipment', 'Cash flow management', 'Growth', 'New Premises', 'One off Projects', 'Refurbishment', 'Other'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Loan Purpose</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Loan Purpose</option>
                                                @foreach($list as $item)
                                                    <option value="{{ $item }}" {{ Helper::getInputValue($index, $data) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (isset($data) && !empty($data))
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = '_status';
                                                    $list = ['Pending', 'Incomplete', 'Complete', 'Approved', 'Disapproved'];
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">Application Status</label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    <option value="">Select Loan Purpose</option>
                                                    @foreach($list as $item)
                                                        <option value="{{ $item }}" {{ $data->status == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'original_default_balance'; @endphp
                                                <label class="form-label" for="{{ $index }}">Original Default Balance </label>
                                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'minimum_daily_repayment'; @endphp
                                                <label class="form-label" for="{{ $index }}">Minimum Daily/Weekly Repayment <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'revenue_repayment_rate'; @endphp
                                                <label class="form-label" for="{{ $index }}">Revenue Repayment Rate(%) </label>
                                                <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'repayment_term_in_working_days'; @endphp
                                                <label class="form-label" for="{{ $index }}">Repayment term in working days </label>
                                                <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card no-lt-rt-pad">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Loan Status</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'broker_id';
                                                $brokers = DB::table('wp_introducers_info')->orderBy('broker_name')->get();
                                            @endphp
                                            @if (!isset($data) && empty($data))
                                                <label class="form-label" for="{{ $index }}">Broker</label>
                                            @else
                                                <label class="form-label" for="{{ $index }}">Introducer</label>
                                            @endif
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select</option>
                                                @foreach($brokers as $broker)
                                                    <option value="{{ $broker->id }}" {{ Helper::getInputValue($index, $data) == $broker->id ? 'selected' : '' }}>{{ $broker->broker_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'deal_status';
                                                $deal_status = DB::table('ga_deal_status')->orderBy('title', 'asc')->get();
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Deal Status <span class="text-danger">*</span></label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select</option>
                                                @foreach($deal_status as $d_status)
                                                    <option value="{{ $d_status->title }}" {{ Helper::getInputValue($index, $data) == $d_status->title ? 'selected' : '' }}>{{ $d_status->title }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (isset($data) && !empty($data))
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = 'aip_status';
                                                    $aip_status = DB::table('ga_aip_status')->orderBy('title', 'asc')->get();
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">AIP Status</label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    <option value="">Select AIP Status</option>
                                                    @foreach($aip_status as $a_status)
                                                        <option value="{{ $a_status->title }}" {{ Helper::getInputValue($index, $data) == $a_status->title ? 'selected' : '' }}>{{ $a_status->title }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = 'business_unit_type';
                                                    $list = ['BCA', 'SIF'];
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">Type <span class="text-danger">*</span></label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    <option value="">Select Type</option>
                                                    @foreach($list as $item)
                                                        <option value="{{ $item }}" {{ Helper::getInputValue($index, $data) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = 'bca_payment_frequency_type_id';
                                                    $payment_frequency_types = DB::table('bca_payment_frequency_types')->orderBy('id', 'desc')->get();
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">Payment Frequency Type <span class="text-danger">*</span></label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    @if (!empty($payment_frequency_types))
                                                        @foreach($payment_frequency_types as $payment_frequency_type)
                                                            <option value="{{ $payment_frequency_type->id }}" {{ Helper::getInputValue($index, $data) == $payment_frequency_type->type ? 'selected' : '' }}>{{ $payment_frequency_type->type }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="">Select Type</option>
                                                    @endif
                                                    
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = 'analyst';
                                                    $committee_members = DB::table('committee_members')->orderBy('member_name', 'asc')->get();
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">Analyst</label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    <option value="">Select Analyst</option>
                                                    @foreach($committee_members as $committee_member)
                                                        <option value="{{ $committee_member->member_name }}" {{ Helper::getInputValue($index, $data) == $committee_member->member_name ? 'selected' : '' }}>{{ $committee_member->member_name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = 'live';
                                                    $list = [1 => 'Yes', 0 => 'No'];
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">LIVE</label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    <option value="">Select</option>
                                                    @foreach($list as $key => $item)
                                                        <option value="{{ $key }}" {{ Helper::getInputValue($index, $data) == $key ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'UnderwritingRevAvg'; @endphp
                                                <label class="form-label" for="{{ $index }}">Under Writing Rev Avg</label>
                                                <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'CollectionDay'; @endphp
                                                <label class="form-label" for="{{ $index }}">Collection Date <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'outstanding_balance'; @endphp
                                                <label class="form-label" for="{{ $index }}">Outstanding Balance</label>
                                                <div class="input-group flex-nowrap">
                                                    <span class="input-group-text attached-text attached-text-left">£</span>
                                                    <input type="number" step=".01" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} prepend-input-right" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                </div>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'arrangement_fee'; @endphp
                                                <label class="form-label" for="{{ $index }}">Arrangement Fee (%)</label>
                                                <div class="input-group flex-nowrap">
                                                    <span class="input-group-text attached-text attached-text-left">%</span>
                                                    <input type="number" min="0" step="0.10" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} prepend-input-right" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                </div>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'repayment_amount'; @endphp
                                                <label class="form-label" for="{{ $index }}">Repayment Amount</label>
                                                <div class="input-group flex-nowrap">
                                                    <span class="input-group-text attached-text attached-text-left">£</span>
                                                    <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} prepend-input-right" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                </div>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'committee_member'; @endphp
                                                <label class="form-label" for="{{ $index }}">Committee Members</label>
                                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = 'status_code';
                                                    $status_codes = range(0, 8);
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">Status Code</label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    @foreach($status_codes as $status_code)
                                                        <option value="{{ $status_code }}" {{ Helper::getInputValue($index, $data) == $status_code ? 'selected' : '' }}>{{ $status_code }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php 
                                                    $index = 'debenture';
                                                    $list = ['Y' => 'Yes', 'N' => 'No'];
                                                @endphp
                                                <label class="form-label" for="{{ $index }}">Debenture</label>
                                                <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                    <option value="">Select</option>
                                                    @foreach($list as $key => $item)
                                                        <option value="{{ $key }}" {{ Helper::getInputValue($index, $data) == $key ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'funded_date'; @endphp
                                            <label class="form-label" for="{{ $index }}">Funded Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (isset($data) && !empty($data))
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'close_date'; @endphp
                                                <label class="form-label" for="{{ $index }}">Close Date</label>
                                                <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'committee_date'; @endphp
                                                <label class="form-label" for="{{ $index }}">Committee Date</label>
                                                <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="mb-3">
                                                @php $index = 'loan_status_message'; @endphp
                                                <label class="form-label" for="{{ $index }}">Reason of Disapprove Application Form</label>
                                                <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="7">{{ Helper::getInputValue($index, $data) }}</textarea>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="mb-3">
                                                @php $index = 'further_info_required'; @endphp
                                                <label class="form-label" for="{{ $index }}">Further Information Required</label>
                                                <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="7">{{ Helper::getInputValue($index, $data) }}</textarea>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="mb-3">
                                                @php $index = 'next_step'; @endphp
                                                <label class="form-label" for="{{ $index }}">Next Steps</label>
                                                <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="7">{{ Helper::getInputValue($index, $data) }}</textarea>
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if (!isset($data) && empty($data))
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'loan_status_message'; @endphp
                                                <label class="form-label" for="{{ $index }}">Reason of Incomplete Application Form</label>
                                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-custom btn-show-processing me-1" type="submit">
                                <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                                <span class="processing-show d-none">Saving...</span>
                                <span class="default-show">Save</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('businesses.loan.info', $id) }}" class="btn btn-light float-right">Discard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('meta')
<meta name="class-to-open" content="businesses">
@endsection

@section('css-lib')
<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-lib')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
@endsection