@extends('index')
@section('title', Helper::getSiteTitle('DD Control'))

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">DD Control - {{ auth()->user()->display_name }}</li>
                </ol>
            </div>
            <h4 class="page-title">DD Control - {{ auth()->user()->display_name }}</h4>
        </div>
    </div>
</div>
<form action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-xl-10 offset-xl-1 mb-2'])
        <div class="col-xl-10 offset-xl-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'group_transaction'; @endphp
                                        <label class="form-label" for="{{ $index }}">Group Transaction?</label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="YES" {{ empty($data) || $data->group_transaction == '1' ? 'selected' : '' }}>YES</option>
                                            <option value="NO" {{ !empty($data) && $data->group_transaction == '0' ? 'selected' : '' }}>NO</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'project_ref'; @endphp
                                        <label class="form-label" for="{{ $index }}">Business Name </label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Business Name">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row additional-companies {{ !empty($data) && $data->group_transaction == '0' ? 'd-none' : '' }}">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'add_company_1'; @endphp
                                            <label class="form-label" for="{{ $index }}">Business Name 1 </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'add_company_2'; @endphp
                                            <label class="form-label" for="{{ $index }}">Business Name 2 </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'add_company_3'; @endphp
                                            <label class="form-label" for="{{ $index }}">Business Name 3 </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'add_company_4'; @endphp
                                            <label class="form-label" for="{{ $index }}">Business Name 4 </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12" id="deal_date_div">
                                    <div class="mb-3">
                                        @php $index = 'deal_date'; @endphp
                                        <label class="form-label" for="{{ $index }}">Deal Date </label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Deal Date">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'ascending_order'; @endphp
                                        <label class="form-label" for="{{ $index }}">Ascending Order?</label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="YES" {{ empty($data) || $data->ascending_order == '1' ? 'selected' : '' }}>YES</option>
                                            <option value="NO" {{ !empty($data) && $data->ascending_order == '0' ? 'selected' : '' }}>NO</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'rev_exp_cutt_off'; @endphp
                                        <label class="form-label" for="{{ $index }}">Rev Exp Cut-off </label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Rev Exp Cut-off">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'currency'; @endphp
                                        <label class="form-label" for="{{ $index }}">Currency</label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="GBP">GBP</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'usd_to_gbp'; @endphp
                                        <label class="form-label" for="{{ $index }}">USD to GBP </label>
                                        <input type="number" step=".01" placeholder="USD to GBP" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'eur_to_gbp'; @endphp
                                        <label class="form-label" for="{{ $index }}">EUR to GBP </label>
                                        <input type="number" step=".01" placeholder="EUR to GBP" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'open_banking'; @endphp
                                        <label class="form-label" for="{{ $index }}">Open Banking </label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="YES" {{ !empty($data) && $data->open_banking == '1' ? 'selected' : '' }}>YES (Read Data From SQL)</option>
                                            <option value="NO" {{ empty($data) || $data->open_banking == '0' ? 'selected' : '' }}>NO (Read Bank Statement Data)</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'limit_date_range'; @endphp
                                        <label class="form-label" for="{{ $index }}">Limit the Date Range? </label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="YES" {{ !empty($data) && $data->limit_date_range == '1' ? 'selected' : '' }}>YES</option>
                                            <option value="NO" {{ empty($data) || $data->limit_date_range == '0' ? 'selected' : '' }}>NO</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'date_range_is_fixed'; @endphp
                                        <label class="form-label" for="{{ $index }}">Date Range is Fixed </label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }} {{ empty($data) || $data->limit_date_range == '0' ? 'item-disabled' : '' }}" name="{{ $index }}" id="{{ $index }}" {{ empty($data) || $data->limit_date_range == '0' ? 'disabled' : '' }}>
                                            <option value="YES" {{ empty($data) || $data->date_range_is_fixed == '1' ? 'selected' : '' }}>YES</option>
                                            <option value="NO" {{ !empty($data) && $data->date_range_is_fixed == '0' ? 'selected' : '' }}>NO</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'start_date'; @endphp
                                        <label class="form-label" for="{{ $index }}">Start Date </label>
                                        <input type="date" placeholder="Start Date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} {{ !empty($data) && $data->limit_date_range == '1' && !empty($data) && $data->date_range_is_fixed == '0' ? 'item-disabled' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" {{ !empty($data) && $data->limit_date_range == '1' && !empty($data) && $data->date_range_is_fixed == '0' ? 'disabled' : '' }}>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'end_date'; @endphp
                                        <label class="form-label" for="{{ $index }}">End Date </label>
                                        <input type="date" placeholder="End Date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }} {{ !empty($data) && $data->limit_date_range == '1' && !empty($data) && $data->date_range_is_fixed == '0' ? 'item-disabled' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" {{ !empty($data) && $data->limit_date_range == '1' && !empty($data) && $data->date_range_is_fixed == '0' ? 'disabled' : '' }}>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'last_number_of_days'; @endphp
                                        <label class="form-label" for="{{ $index }}">Last No. of Days </label>
                                        <input type="number" placeholder="Last No. of Days" class="form-control item-disabled {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" {{ !empty($data) && $data->limit_date_range == '1' && !empty($data) && $data->date_range_is_fixed == '1' ? 'disabled' : '' }}>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'manual_review'; @endphp
                                        <label class="form-label" for="{{ $index }}">Manual Review </label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="1" {{ !empty($data) && $dd_control->manual_review == 1 ? 'selected' : '' }}>YES</option>
                                            <option value="0" {{ empty($data) || $dd_control->manual_review == 0 ? 'selected' : '' }}>NO</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        @php $index = 'card_repayment'; @endphp
                                        <label class="form-label" for="{{ $index }}">Card Repayments </label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="1" {{ !empty($data) && isset($data->card_repayment) && $data->card_repayment == 1 ? 'selected' : '' }}>YES</option>
                                            <option value="0" {{ empty($data) || (isset($data->card_repayment) && $data->card_repayment == 0) ? 'selected' : '' }}>NO</option>
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <button class="btn btn-custom btn-show-processing me-1" type="submit">
                                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                                        <span class="processing-show d-none">Saving...</span>
                                        <span class="default-show">Save</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('meta')
<meta name="class-to-open" content="dd-control-form">
@endsection

@section('css-lib')
<link href="{{ asset('vendor/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-lib')
<script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('vendor/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
@endsection