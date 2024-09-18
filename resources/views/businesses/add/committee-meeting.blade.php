@extends('index')
@section('title', Helper::getSiteTitle('Committee Meeting'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Committee Meeting</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Committee Meeting Notes' }}</h4>
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
                                <h4 class="header-title mb-0">Meeting Details</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'meeting_date'; @endphp
                                            <label class="form-label" for="{{ $index }}">Committee Meeting Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'credit_strengths'; @endphp
                                            <label class="form-label" for="{{ $index }}">Credit Strengths <span class="text-danger">*</span></label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'credit_weekness'; @endphp
                                            <label class="form-label" for="{{ $index }}">Credit Weaknesses <span class="text-danger">*</span></label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="{{ $index }}">Committee Members Opinion & Rationale</label>
                                            <table class="table table-bordered cust_info w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Member</th>
                                                        <th>Vote</th>
                                                        <th>Rationale</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($committee_opinions) && !empty($committee_opinions))
                                                        @foreach ($committee_opinions as $committee_opinion)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="c_option_id[]" value="{{ Helper::getInputValue('id', $committee_opinion) }}">
                                                                    @php $index = 'mem_name'; @endphp
                                                                    <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $committee_opinion) }}" disabled>
                                                                </td>
                                                                <td>
                                                                    @php 
                                                                        $index = 'vote';
                                                                        $list = ['Yes', 'No', 'Absent'];
                                                                    @endphp
                                                                    <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}[]" id="{{ $index }}">
                                                                        <option value="">Select</option>
                                                                        @foreach($list as $item)
                                                                            <option value="{{ $item }}" {{ Helper::getInputValue($index, $committee_opinion) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    @php $index = 'rationale'; @endphp
                                                                    <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}[]" rows="4">{{ $committee_opinion->rationale }}</textarea>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'info_required'; @endphp
                                            <label class="form-label" for="{{ $index }}">Further Information Required (if any) <span class="text-danger">*</span></label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'major_reservations'; @endphp
                                            <label class="form-label" for="{{ $index }}">Major Reservations <span class="text-danger">*</span></label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'decision'; @endphp
                                            <label class="form-label" for="{{ $index }}">Decision and Next Steps <span class="text-danger">*</span></label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'video_name'; @endphp
                                            <label class="form-label" for="{{ $index }}">Upload Video </label>
                                            <input type="file" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
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
                                <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                                <span class="processing-show d-none">Saving...</span>
                                <span class="default-show">Save</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('keywords') }}" class="btn btn-light float-right">Discard</a>
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