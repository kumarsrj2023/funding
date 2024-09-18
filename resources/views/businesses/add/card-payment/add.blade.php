@extends('index')
@section('title', Helper::getSiteTitle('Director Info'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Director Info</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Director Info' }}</h4>
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
                                <h4 class="header-title mb-0">Personal Details</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'title';
                                                $list = ['Mr.', 'Mrs.', 'Ms.', 'Dr.'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Title </label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select title</option>
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
                                            @php $index = 'first_name'; @endphp
                                            <label class="form-label" for="{{ $index }}">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'surname'; @endphp
                                            <label class="form-label" for="{{ $index }}">Surname <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'phone'; @endphp
                                            <label class="form-label" for="{{ $index }}">Phone no.</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'email'; @endphp
                                            <label class="form-label" for="{{ $index }}">Email </label>
                                            <input type="email" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'date_of_birth'; @endphp
                                            <label class="form-label" for="{{ $index }}">Date of Birth</label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'gender';
                                                $list = ['Male', 'Female', 'Unknown'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Gender</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Gender</option>
                                                @foreach($list as $item)
                                                    <option value="{{ $item }}" {{ Helper::getInputValue($index, $data) == $item ? 'selected' : '' }}>{{ $item }}</option>
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
                                <h4 class="header-title mb-0">Residence Details</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'address_simple_value'; @endphp
                                            <label class="form-label" for="{{ $index }}">Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'address_type';
                                                $list = ['Service Address'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Address Type</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Address Type</option>
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
                                            @php $index = 'address_street'; @endphp
                                            <label class="form-label" for="{{ $index }}">Address Street </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'address_city'; @endphp
                                            <label class="form-label" for="{{ $index }}">City </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'address_postal_code'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postal code </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'nationality'; @endphp
                                            <label class="form-label" for="{{ $index }}">Nationality </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'director_type';
                                                $list = ['Person', 'Other'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Director Type</label>
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
                                            @php $index = 'date_appointed'; @endphp
                                            <label class="form-label" for="{{ $index }}">Date Appointed </label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'position_name'; @endphp
                                            <label class="form-label" for="{{ $index }}">Position Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'present_appointments'; @endphp
                                            <label class="form-label" for="{{ $index }}">Present Appointments </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'occupation'; @endphp
                                            <label class="form-label" for="{{ $index }}">Occupation </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'ni_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">NI Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
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
                            <a href="{{ route('businesses.director.info', $id) }}" class="btn btn-light float-right">Discard</a>
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