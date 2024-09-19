@extends('index')
@section('title', Helper::getSiteTitle('Customer Info'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Customer Info</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Customer Info' }}</h4>
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
                                            <label class="form-label" for="{{ $index }}">Title <span class="text-danger">*</span></label>
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
                                            @php $index = 'middle_name'; @endphp
                                            <label class="form-label" for="{{ $index }}">Middle Name</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'last_name'; @endphp
                                            <label class="form-label" for="{{ $index }}">Last Name <span class="text-danger">*</span></label>
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
                                            <label class="form-label" for="{{ $index }}">Email <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'dob'; @endphp
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
                                                $index = 'marital_status';
                                                $list = ['Married', 'Unmarried', 'Civil Partnership', 'Divorced', 'Legally Separated'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Marital status</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select marital status</option>
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
                                            @php $index = 'number_of_dependents'; @endphp
                                            <label class="form-label" for="{{ $index }}">Number of dependents</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select number of dependents</option>
                                                @for($dependendents = 1; $dependendents <= 15; $dependendents++)
                                                    <option value="{{ $dependendents }}" {{ Helper::getInputValue($index, $data) == $dependendents ? 'selected' : '' }}>{{ $dependendents }}</option>
                                                @endfor
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'identification_document'; @endphp
                                            <label class="form-label" for="{{ $index }}">Identification document</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select document</option>
                                                <option value="driver_license" {{ Helper::getInputValue($index, $data) == 'driver_license' ? 'selected' : '' }}>Driver's License</option>
                                                <option value="passport" {{ Helper::getInputValue($index, $data) == 'passport' ? 'selected' : '' }}>Passport</option>
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
                                            @php $index = 'country'; @endphp
                                            <label class="form-label" for="{{ $index }}">Country <span class="text-danger">*</span></label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select country</option>
                                                <option value="england" {{ Helper::getInputValue($index, $data) == 'england' ? 'selected' : '' }}>England</option>
                                                <option value="northern-ireland" {{ Helper::getInputValue($index, $data) == 'northern-ireland' ? 'selected' : '' }}>Northern Ireland</option>
                                                <option value="scotland" {{ Helper::getInputValue($index, $data) == 'scotland' ? 'selected' : '' }}>Scotland</option>
                                                <option value="wales" {{ Helper::getInputValue($index, $data) == 'wales' ? 'selected' : '' }}>Wales</option>
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'county'; @endphp
                                            <label class="form-label" for="{{ $index }}">County</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select county</option>
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'address'; @endphp
                                            <label class="form-label" for="{{ $index }}">Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'city'; @endphp
                                            <label class="form-label" for="{{ $index }}">City <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'state'; @endphp
                                            <label class="form-label" for="{{ $index }}">State</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'postal_code'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postal code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'years_at_address';
                                                $list = ['0-3 months', '3-6 months', '6-12 months', '1-2 years', '3-5 years', '5-10 years', '10 years+'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Years at home address</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select years</option>
                                                @foreach($list as $item)
                                                    <option value="{{ $item }}" {{ Helper::getInputValue($index, $data) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'role_in_business'; @endphp
                                            <label class="form-label" for="{{ $index }}">What is your role within the business?</label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="7">{{ Helper::getInputValue($index, $data) }}</textarea>
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
                                <h4 class="header-title mb-0">Security</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'ownership';
                                                $list = ['Home Owner (Own Home)', 'Home Owner (Investment Property)', 'Private Tenant', 'Council Tenant', 'Living Rent Free', 'Other'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Ownership</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select ownership</option>
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
                                            @php $index = 'proof_of_address'; @endphp
                                            <label class="form-label" for="{{ $index }}">Proof of home address</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select your proof</option>
                                                <option value="council_tax" {{ Helper::getInputValue($index, $data) == 'council_tax' ? 'selected' : '' }}>Council Tax</option>
                                                <option value="bank_statement" {{ Helper::getInputValue($index, $data) == 'bank_statement' ? 'selected' : '' }}>Bank Statement</option>
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 council-tax {{ !empty($data) && $data->proof_of_address == 'council_tax' ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'council_tax_doc'; @endphp
                                            <label class="form-label" for="{{ $index }}">Council Tax</label>
                                            <input type="file" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 bank-statement {{ !empty($data) && $data->proof_of_address == 'bank_statement' ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'bank_statement_doc'; @endphp
                                            <label class="form-label" for="{{ $index }}">Bank Statement</label>
                                            <input type="file" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'sec_country'; @endphp
                                            <label class="form-label" for="{{ $index }}">Country</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select country</option>
                                                <option value="england" {{ Helper::getInputValue($index, $data) == 'england' ? 'selected' : '' }}>England</option>
                                                <option value="northern-ireland" {{ Helper::getInputValue($index, $data) == 'northern-ireland' ? 'selected' : '' }}>Northern Ireland</option>
                                                <option value="scotland" {{ Helper::getInputValue($index, $data) == 'scotland' ? 'selected' : '' }}>Scotland</option>
                                                <option value="wales" {{ Helper::getInputValue($index, $data) == 'wales' ? 'selected' : '' }}>Wales</option>
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'sec_county'; @endphp
                                            <label class="form-label" for="{{ $index }}">County</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select county</option>
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'sec_address'; @endphp
                                            <label class="form-label" for="{{ $index }}">Address</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'sec_city'; @endphp
                                            <label class="form-label" for="{{ $index }}">City</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'sec_state'; @endphp
                                            <label class="form-label" for="{{ $index }}">State</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'sec_postal_code'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postal code</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'approximate_value'; @endphp
                                            <label class="form-label" for="{{ $index }}">Approximate value (GBP/£)</label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                        <div class="mb-3">
                                            @php $index = 'mortage_outstanding'; @endphp
                                            <label class="form-label" for="{{ $index }}">Mortgage outstanding (GBP/£)</label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            <div class="form-check form-checkbox-dark mb-2 home-owner {{ !empty($data) && ($data->ownership == 'Home Owner (Own Home)' || $data->ownership == 'Home Owner (Investment Property)') ? '' : 'd-none'  }}">
                                                @php $index = 'personal_guarantee'; @endphp
                                                <input type="checkbox" class="form-check-input {{ $index }}" id="{{ $index }}" name="{{ $index }}" value="true" {{ Helper::getInputValue($index, $data) == 'true' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $index }}">
                                                    <p class="mb-0">Would you be willing to provide a personal guarantee?</p>
                                                </label>
                                            </div>
                                            <div class="form-check form-checkbox-dark mb-2">
                                                @php $index = 'floating_charge'; @endphp
                                                <input type="checkbox" class="form-check-input {{ $index }}" id="{{ $index }}" name="{{ $index }}" value="true" {{ Helper::getInputValue($index, $data) == 'true' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $index }}">
                                                    <p class="mb-0">Would you be willing to provide a floating charge on the business?</p>
                                                </label>
                                            </div>
                                            <div class="form-check form-checkbox-dark">
                                                @php $index = 'banking_access'; @endphp
                                                <input type="checkbox" class="form-check-input {{ $index }}" id="{{ $index }}" name="{{ $index }}" value="true" {{ Helper::getInputValue($index, $data) == 'true' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $index }}">
                                                    <p class="mb-0">Would you be prepared to give us open banking access to your account?</p>
                                                </label>
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