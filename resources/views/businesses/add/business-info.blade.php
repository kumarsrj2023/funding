@extends('index')
@section('title', Helper::getSiteTitle('Business Info'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Business Info</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Business Info' }}</h4>
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
                                <h4 class="header-title mb-0">Business Details</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'business_name'; @endphp
                                            <label class="form-label" for="{{ $index }}">Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'industry';
                                                $list = ['Accommodation and Food Services', 'Administrative and Support Services', 'Aerospace and aviation', 'Agriculture', 'Forestry and Fishing', 'Arts, Entertainment and Recreation', 'Automobiles and Components', 'Banking', 'Commercial and Professional Services', 'Construction and building', 'Consumer Goods & Applicances', 'Consumer Services', 'CSR and governance', 'Education', 'Electricity, Gas, Steam and Air Conditioning Supply', 'Financial Services', 'Food, Beverage, and Tobacco', 'Health Care Equipment and Services', 'Household and Personal Products', 'Human Health and Social Work Activities', 'Import export', 'Information management & data protection', 'Insurance', 'IT, Software & Services', 'Manufacturing and processing', 'Media and Entertainment', 'Mining and Quarrying', 'Online Retail', 'Pharmaceuticals, Biotechnology, and Life Sciences', 'Professional, Scientific and Technical Activities', 'Quality and business improvement', 'Real Estate Activities', 'Security', 'Specialist Engineering, Infrastructure & Contractors', 'Technology Hardware and Equipment', 'Telecommunication Services', 'Transportation, logistics and Storage', 'Utilities', 'Water Supply; Sewerage, Waste Management and Remediation Activities', 'Wholesale and Retail Trade'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Industry Sector </label>
                                            <select class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Industry</option>
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
                                            @php $index = 'registration_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Registration Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'unique_tax_reference_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Unique Tax Reference Number (UTR) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'time_in_business';
                                                $list = ['0-3 months', '3-6 months', '6-12 months', '1-2 years', '3-5 years', '5-10 years', '10 years+'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Time In Business </label>
                                            <select class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Business Time</option>
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
                                            @php $index = 'monthly_turnover'; @endphp
                                            <label class="form-label" for="{{ $index }}">Current Monthly Turnover</label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'date_of_incorporation'; @endphp
                                            <label class="form-label" for="{{ $index }}">Date of Incorporation</label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'ProjectRef'; @endphp
                                            <label class="form-label" for="{{ $index }}">Project Ref </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'DealDate'; @endphp
                                            <label class="form-label" for="{{ $index }}">Deal Date </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
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
                                <h4 class="header-title mb-0">Registered Address</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'country'; @endphp
                                            <label class="form-label" for="{{ $index }}">Country </label>
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
                                            <label class="form-label" for="{{ $index }}">Business Address </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'city'; @endphp
                                            <label class="form-label" for="{{ $index }}">Town/City </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'postal_code'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postal Code </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
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
                                <h4 class="header-title mb-0">Trading Address</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row trading-address">
                                    <div class="cust_title">
                                        <div class="form-check form-checkbox-dark mb-2">
                                            @php $index = 'checked_address'; @endphp
                                            <input type="checkbox" class="form-check-input {{ $index }}" id="{{ $index }}" name="{{ $index }}" {{ Helper::getInputValue($index, $data) == true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $index }}">
                                                <p class="mb-0">Registered Address is the same as Trading Address</p>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'trcountry'; @endphp
                                            <label class="form-label" for="{{ $index }}">Country </label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }} {{ $index }}" name="{{ $index }}" id="{{ $index }}" {{ $data->checked_address == true ? 'disabled' : '' }}>
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
                                            @php $index = 'trcounty'; @endphp
                                            <label class="form-label" for="{{ $index }}">County</label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }} {{ $index }}" name="{{ $index }}" id="{{ $index }}" {{ $data->checked_address == true ? 'disabled' : '' }}>
                                                <option value="">Select county</option>
                                            </select>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'traddress'; @endphp
                                            <label class="form-label" for="{{ $index }}">Business Address </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" {{ $data->checked_address == true ? 'disabled' : '' }}>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'trcity'; @endphp
                                            <label class="form-label" for="{{ $index }}">Town/City </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" {{ $data->checked_address == true ? 'disabled' : '' }}>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'trpostal_code'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postal Code </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" {{ $data->checked_address == true ? 'disabled' : '' }}>
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
                                            <label class="form-label" for="{{ $index }}">Years at Business Address </label>
                                            <select class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Business Address</option>
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
                                            @php $index = 'website_address'; @endphp
                                            <label class="form-label" for="{{ $index }}">Website Address </label>
                                            <input type="url" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="https://abc.com">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'monitored_company_email'; @endphp
                                            <label class="form-label" for="{{ $index }}">Monitored Company Email </label>
                                            <input type="email" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" placeholder="test@gmail.com">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'switchboard_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Switchboard Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'switchboard_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Switchboard Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'reason_for_funding'; @endphp
                                            <label class="form-label" for="{{ $index }}">Please let us know the reason you are looking for funding</label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'future_business_plan'; @endphp
                                            <label class="form-label" for="{{ $index }}">Please outline your future business plan</label>
                                            <textarea class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'forecast'; @endphp
                                            <label class="form-label" for="{{ $index }}">Please upload a 12 month forecast (if you have one)</label>
                                            <input type="file" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}[]" value="{{ Helper::getInputValue($index, $data) }}" multiple>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-header mb-3">
                                        <h4 class="header-title">Director 1</h4>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_title'; @endphp
                                            <label class="form-label" for="{{ $index }}">Title </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_forename'; @endphp
                                            <label class="form-label" for="{{ $index }}">Forename </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_middlename'; @endphp
                                            <label class="form-label" for="{{ $index }}">Middle Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_surname'; @endphp
                                            <label class="form-label" for="{{ $index }}">Surname </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_dob'; @endphp
                                            <label class="form-label" for="{{ $index }}">Date of Birth </label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_housenumber'; @endphp
                                            <label class="form-label" for="{{ $index }}">House Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_housename'; @endphp
                                            <label class="form-label" for="{{ $index }}">House Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_postcode'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postcode </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_postcode'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postcode </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <h4 class="mb-3"><span class="card-header p-2">Time At Address</span></h4>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_years'; @endphp
                                            <label class="form-label" for="{{ $index }}">Years </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_1_months'; @endphp
                                            <label class="form-label" for="{{ $index }}">Months </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-header mb-3">
                                        <h4 class="header-title">Director 2</h4>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_title'; @endphp
                                            <label class="form-label" for="{{ $index }}">Title </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_forename'; @endphp
                                            <label class="form-label" for="{{ $index }}">Forename </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_middlename'; @endphp
                                            <label class="form-label" for="{{ $index }}">Middle Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_surname'; @endphp
                                            <label class="form-label" for="{{ $index }}">Surname </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_dob'; @endphp
                                            <label class="form-label" for="{{ $index }}">Date of Birth </label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_housenumber'; @endphp
                                            <label class="form-label" for="{{ $index }}">House Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_housename'; @endphp
                                            <label class="form-label" for="{{ $index }}">House Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_postcode'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postcode </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_postcode'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postcode </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <h4 class="mb-3"><span class="card-header p-2">Time At Address</span></h4>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_years'; @endphp
                                            <label class="form-label" for="{{ $index }}">Years </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_2_months'; @endphp
                                            <label class="form-label" for="{{ $index }}">Months </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-header mb-3">
                                        <h4 class="header-title">Director 3</h4>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_title'; @endphp
                                            <label class="form-label" for="{{ $index }}">Title </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_forename'; @endphp
                                            <label class="form-label" for="{{ $index }}">Forename </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_middlename'; @endphp
                                            <label class="form-label" for="{{ $index }}">Middle Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_surname'; @endphp
                                            <label class="form-label" for="{{ $index }}">Surname </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_dob'; @endphp
                                            <label class="form-label" for="{{ $index }}">Date of Birth </label>
                                            <input type="date" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_housenumber'; @endphp
                                            <label class="form-label" for="{{ $index }}">House Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_housename'; @endphp
                                            <label class="form-label" for="{{ $index }}">House Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_postcode'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postcode </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_postcode'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postcode </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <h4 class="mb-3"><span class="card-header p-2">Time At Address</span></h4>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_years'; @endphp
                                            <label class="form-label" for="{{ $index }}">Years </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'director_3_months'; @endphp
                                            <label class="form-label" for="{{ $index }}">Months </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
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
                                <h4 class="header-title mb-0">PLEASE UPLOAD A COPY OF YOUR MOST RECENT ANNUAL FINANCIAL STATEMENTS OR INSERT THE DETAILS BELOW</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'inc_stmt'; @endphp
                                            <label class="form-label" for="{{ $index }}">Income Statement </label>
                                            <input type="file" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}[]" value="{{ Helper::getInputValue($index, $data) }}" multiple>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-header mb-3">
                                        <h4 class="header-title mb-0">Income Statement</h4>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            <table class="w-100">
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td>Last Financial Year</td>
                                                        <td>Previous Year</td>
                                                        <td>Prior Year</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Revenue</td>
                                                        <td>
                                                            @php $index = 'revenue_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'revenue_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'revenue_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cost of Goods Sold</td>
                                                        <td>
                                                            @php $index = 'cgs_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'cgs_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'cgs_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gross Profit</td>
                                                        <td>
                                                            @php $index = 'gp_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'gp_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'gp_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Operating Expenses</td>
                                                        <td>
                                                            @php $index = 'ox_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'ox_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'ox_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>EBITDA</td>
                                                        <td>
                                                            @php $index = 'e_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'e_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'e_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Non-Cash Expenses</td>
                                                        <td>
                                                            @php $index = 'ncx_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'ncx_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'ncx_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>EBIT</td>
                                                        <td>
                                                            @php $index = 'eb_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'eb_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'eb_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Taxes</td>
                                                        <td>
                                                            @php $index = 'tax_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'tax_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'tax_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Net Profit After Tax</td>
                                                        <td>
                                                            @php $index = 'net_inc_year_1'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'net_inc_year_2'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                        <td>
                                                            @php $index = 'net_inc_year_3'; @endphp
                                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-header mb-3">
                                        <h4 class="header-title mb-0">Balance Sheet</h4>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'assets'; @endphp
                                            <label class="form-label" for="{{ $index }}">Debtors </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'liabilities'; @endphp
                                            <label class="form-label" for="{{ $index }}">Creditors </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'current_assets'; @endphp
                                            <label class="form-label" for="{{ $index }}">Current Assets </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'current_liabilities'; @endphp
                                            <label class="form-label" for="{{ $index }}">Current Liabilities </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'fixed_assets'; @endphp
                                            <label class="form-label" for="{{ $index }}">Fixed Assets </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'long_term_liabilities'; @endphp
                                            <label class="form-label" for="{{ $index }}">Long-Term Liabilities </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'other_assets'; @endphp
                                            <label class="form-label" for="{{ $index }}">Other Assets </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'other_liabilities'; @endphp
                                            <label class="form-label" for="{{ $index }}">Other Liabilities </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'total_assets'; @endphp
                                            <label class="form-label" for="{{ $index }}">Total Assets </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'total_liabilities'; @endphp
                                            <label class="form-label" for="{{ $index }}">Total Liabilities </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}" disabled>
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'stockholder_equity'; @endphp
                                            <label class="form-label" for="{{ $index }}">Share Capital </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'total_equity'; @endphp
                                            <label class="form-label" for="{{ $index }}">Retained Earnings </label>
                                            <input type="number" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
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
                                <h4 class="header-title mb-0">OUTSTANDING FINANCE</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="oustanding_finance income_statement mb-3">
                                            <table class="table table-bordered cust_info w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Lender</th>
                                                        <th>Amount Outstanding</th>
                                                        <th>Remaining Term (in Months)</th>
                                                        <th>Type</th>
                                                        <th>Fixed Repayment (Monthly)</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="repeater of-repeater">
                                                    <tr class="node">
                                                        <td>
                                                            @php $index = 'lender'; @endphp
                                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'amount_outstanding'; @endphp
                                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php $index = 'term'; @endphp
                                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td>
                                                            @php 
                                                                $index = 'type';
                                                                $list = ['Unsecured Finance', 'Venture Debt', 'Asset Finance', 'Supply Chain Finance', 'Bridging Finance', 'Mortgage', 'Invoice Finance', 'Merchant Cash Advance'];
                                                            @endphp
                                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                                <option value="">Select Type</option>
                                                                @foreach($list as $item)
                                                                    <option value="{{ $item }}" {{ Helper::getInputValue($index, $data) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            @php $index = 'fixed_repayment'; @endphp
                                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-danger me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <a href="javascript:void(0)" class="insert-repeater text-decoration-underline" data-repeaterclass="of-repeater">ADD CREDIT LINES HERE</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card no-lt-rt-pad">
                            <div class="card-header">
                                <h4 class="header-title mb-0">PLEASE UPLOAD BANK STATEMENTS BELOW</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'bank_stmt'; @endphp
                                            <label class="form-label" for="{{ $index }}">Bank Statement </label>
                                            <input type="file" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}[]" value="{{ Helper::getInputValue($index, $data) }}" multiple>
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
                                <h4 class="header-title mb-0">Bank Account</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'name_of_bank'; @endphp
                                            <label class="form-label" for="{{ $index }}">Name of Bank </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'branch_name'; @endphp
                                            <label class="form-label" for="{{ $index }}">Branch Name </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'branch_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Branch Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php 
                                                $index = 'account_type';
                                                $list = ['Cheque', 'Investment', 'Savings'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Account Type </label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Account Type</option>
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
                                            @php $index = 'account_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Account Number </label>
                                            <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <h4 class="header-title mb-0">Upload Proof of Bank Details</h4>
                                    </div>
                                    <div class="card-body compact-card-body">
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <div class="mb-3">
                                                    @php $index = 'bank_detail'; @endphp
                                                    <label class="form-label" for="{{ $index }}">Bank Statement </label>
                                                    <input type="file" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}[]" value="{{ Helper::getInputValue($index, $data) }}" multiple>
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
                            <a href="{{ route('businesses.business.info', $id) }}" class="btn btn-light float-right">Discard</a>
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