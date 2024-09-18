@extends('index')
@section('title', Helper::getSiteTitle(isset($data) && !empty($data) ? 'Update Keyword' : 'Add Keyword'))

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Keywords</a></li>
                    <li class="breadcrumb-item active">{{ isset($data) && !empty($data) ? 'Update Keyword' : 'Add Keyword' }}</li>
                </ol>
            </div>
            <h4 class="page-title">{{ isset($data) && !empty($data) ? 'Update Keyword' : 'Add Keyword' }}</h4>
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
                                        @php $index = 'SearchTerm1'; @endphp
                                        <label class="form-label" for="{{ $index }}">Search Term 1</label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'SearchTerm2'; @endphp
                                        <label class="form-label" for="{{ $index }}">Search Term 2</label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'Excluding'; @endphp
                                        <label class="form-label" for="{{ $index }}">Excluding</label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'Amount'; @endphp
                                        <label class="form-label" for="{{ $index }}">Amount</label>
                                        <input type="number" step=".01" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'Description'; @endphp
                                        <label class="form-label" for="{{ $index }}">Description</label>
                                        <select class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="">Select</option>
                                            @if(!$keywords_sort_order->isEmpty())
                                                @foreach($keywords_sort_order as $keyword_sort_order)
                                                    <option value="{{ trim($keyword_sort_order->Field) }}" {{ isset($data) && !empty($data) && !empty($data->Description) && $data->Description == trim($keyword_sort_order->Field) ? 'selected' : '' }}>{{ trim($keyword_sort_order->Field) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'Description2'; @endphp
                                        <label class="form-label" for="{{ $index }}">Description 2</label>
                                        <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}">
                                        @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                        @php $index = 'DealSpecific'; @endphp
                                        <label class="form-label" for="{{ $index }}">Deal Specific</label>
                                        <select class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            <option value="">Select</option>
                                            @if(!$bank_statements->isEmpty())
                                                @foreach($bank_statements as $bank_statement)
                                                    <option value="{{ trim($bank_statement->ProjectRef) }}" {{ isset($data) && !empty($data) && !empty($data->DealSpecific) && $data->DealSpecific == trim($bank_statement->ProjectRef) ? 'selected' : '' }}>{{ trim($bank_statement->ProjectRef) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
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
                        <span class="default-show">Save keyword</span>
                    </button>
                </div>
                <div class="col-6">
                    @if(!empty($data))
                        <a href="javascript:void(0);" class="btn btn-danger float-right remove-item-button" data-id="{{ $data->id }}">Delete keyword</a>
                    @endif
                    <a href="{{ route('keywords') }}" class="btn btn-light float-right">Discard</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('keywords.remove') }}">
<meta name="class-to-open" content="keywords">
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