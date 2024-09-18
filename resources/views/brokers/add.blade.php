@extends('index')
@section('title', Helper::getSiteTitle(isset($data) && !empty($data) ? 'Update Broker' : 'Add Broker'))

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('broker.list') }}">Broker List</a></li>
                    <li class="breadcrumb-item active">{{ isset($data) && !empty($data) ? 'Update Broker' : 'Add Broker' }}</li>
                </ol>
            </div>
            <h4 class="page-title">{{ isset($data) && !empty($data) ? 'Update Broker' : 'Add Broker' }}</h4>
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
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'broker_name'; @endphp
                                        <label class="form-label" for="{{ $index }}">Broker Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'broker_email'; @endphp
                                        <label class="form-label" for="{{ $index }}">Broker Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'region'; @endphp
                                        <label class="form-label" for="{{ $index }}">Region <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'phone_no'; @endphp
                                        <label class="form-label" for="{{ $index }}">Phone <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'business_name'; @endphp
                                        <label class="form-label" for="{{ $index }}">Business Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'introducer'; @endphp
                                        <label class="form-label" for="{{ $index }}">Introducer <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'broker_note'; @endphp
                                        <label class="form-label" for="{{ $index }}">Broker Note <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
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
                        <span class="default-show">Save broker</span>
                    </button>
                </div>
                <div class="col-6">
                    @if(!empty($data))
                        <a href="javascript:void(0);" class="btn btn-danger float-right remove-item-button" data-id="{{ $data->id }}">Delete broker</a>
                    @endif
                    <a href="{{ route('broker.list') }}" class="btn btn-light float-right">Discard</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('broker.list.remove') }}">
<meta name="class-to-open" content="brokers">
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