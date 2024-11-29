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
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'Loan Info' }}</h4>
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
                                            <label class="form-label" for="{{ $index }}">Loan Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php
                                            $index = 'loan_purpose';
                                            $list = ['Working Capital', 'Stock', 'Additional Staff', 'New Equipment',
                                            'Cash flow management', 'Growth', 'New Premises', 'One off Projects',
                                            'Refurbishment', 'Other'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Loan Purpose</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Loan Purpose</option>
                                                @foreach($list as $item)
                                                <option value="{{ $item }}" {{ Helper::getInputValue($index,
                                                    $data)==$item ? 'selected' : '' }}>{{ $item }}</option>
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
                                            $index = 'loan_type';
                                            $loan_type = ['BCA','SIF'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Loan Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Loan type</option>
                                                @foreach($loan_type as $value)
                                                <option value="{{ $value }}" {{ Helper::getInputValue($index,
                                                    $data)==$value ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card no-lt-rt-pad">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Important Dates</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'application_date'; @endphp
                                            <label class="form-label" for="{{ $index }}">Application Date<span
                                                    class="text-danger"></span></label>
                                            <input type="date"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'credit_committee_date'; @endphp
                                            <label class="form-label" for="{{ $index }}">Credit Committee Date<span
                                                    class="text-danger"></span></label>
                                            <input type="date"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'funding_date'; @endphp
                                            <label class="form-label" for="{{ $index }}">Funding Date<span
                                                    class="text-danger"></span></label>
                                            <input type="date"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'closed_date'; @endphp
                                            <label class="form-label" for="{{ $index }}">Closed Date<span
                                                    class="text-danger"></span></label>
                                            <input type="date"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card no-lt-rt-pad">
                            <div class="card-header">
                                <h4 class="header-title mb-0">After Funding the Application</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'transferred_amount'; @endphp
                                            <label class="form-label" for="{{ $index }}">Transferred Amount </label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text attached-text attached-text-left">£</span>
                                                <input type="number" step=".01"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} prepend-input-right"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}">
                                            </div>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'advance_requested'; @endphp
                                            <label class="form-label" for="{{ $index }}">Advance Amount</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text attached-text attached-text-left">£</span>
                                                <input type="number" step=".01"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} prepend-input-right"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}">
                                            </div>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'arrangement_fee'; @endphp
                                            <label class="form-label" for="{{ $index }}">Arrangement Fee</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text attached-text attached-text-left">£</span>
                                                <input type="number" step=".01"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} prepend-input-right"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}">
                                            </div>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'multiple'; @endphp
                                            <label class="form-label" for="{{ $index }}">Multiple<span
                                                    class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'total_repayable_amount'; @endphp
                                            <label class="form-label" for="{{ $index }}">Total Repayable Amount</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text attached-text attached-text-left">£</span>
                                                <input type="number" step=".01"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} prepend-input-right"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}">
                                            </div>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'repayment_type'; @endphp
                                            <label class="form-label" for="{{ $index }}">Repayment Type<span
                                                    class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'repayment_term'; @endphp
                                            <label class="form-label" for="{{ $index }}">Repayment Term<span
                                                    class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'minimum_daily_weekly_repayment'; @endphp
                                            <label class="form-label" for="{{ $index }}">Minimum Daily/Weekly
                                                Repayment<span class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'receipt_percentage'; @endphp
                                            <label class="form-label" for="{{ $index }}">Receipt Percentage<span
                                                    class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card no-lt-rt-pad">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Origination</h4>
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
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select</option>
                                                @foreach($brokers as $broker)
                                                <option value="{{ $broker->id }}" {{ Helper::getInputValue($index,
                                                    $data)==$broker->id ? 'selected' : '' }}>{{ $broker->broker_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'introducer_brokerage'; @endphp
                                            <label class="form-label" for="{{ $index }}">Introducer Brokerage<span
                                                    class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'commission_percentage'; @endphp
                                            <label class="form-label" for="{{ $index }}">Commission Percentage<span
                                                    class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'commision_to_pay'; @endphp
                                            <label class="form-label" for="{{ $index }}">Commision to Pay<span
                                                    class="text-danger"></span></label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-custom btn-show-processing me-1" type="submit">
                                <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status"
                                    aria-hidden="true"></span>
                                <span class="processing-show d-none">Saving...</span>
                                <span class="default-show">Save</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('businesses.loan.info', $id) }}"
                                class="btn btn-light float-right">Discard</a>
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
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-lib')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>

@endsection