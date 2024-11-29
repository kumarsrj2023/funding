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
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'Business Info' }}</h4>
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
                                            <label class="form-label" for="{{ $index }}">Business Name <span
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
                                            $index = 'industry';
                                            $list = ['Accommodation and Food Services', 'Administrative and Support
                                            Services', 'Aerospace and aviation', 'Agriculture', 'Forestry and Fishing',
                                            'Arts, Entertainment and Recreation', 'Automobiles and Components',
                                            'Banking', 'Commercial and Professional Services', 'Construction and
                                            building', 'Consumer Goods & Applicances', 'Consumer Services', 'CSR and
                                            governance', 'Education', 'Electricity, Gas, Steam and Air Conditioning
                                            Supply', 'Financial Services', 'Food, Beverage, and Tobacco', 'Health Care
                                            Equipment and Services', 'Household and Personal Products', 'Human Health
                                            and Social Work Activities', 'Import export', 'Information management & data
                                            protection', 'Insurance', 'IT, Software & Services', 'Manufacturing and
                                            processing', 'Media and Entertainment', 'Mining and Quarrying', 'Online
                                            Retail', 'Pharmaceuticals, Biotechnology, and Life Sciences', 'Professional,
                                            Scientific and Technical Activities', 'Quality and business improvement',
                                            'Real Estate Activities', 'Security', 'Specialist Engineering,
                                            Infrastructure & Contractors', 'Technology Hardware and Equipment',
                                            'Telecommunication Services', 'Transportation, logistics and Storage',
                                            'Utilities', 'Water Supply; Sewerage, Waste Management and Remediation
                                            Activities', 'Wholesale and Retail Trade'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Industry Sector </label>
                                            <select
                                                class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select Industry</option>
                                                @foreach($list as $item)
                                                @php
                                                $normalizedItem = preg_replace('/\s+/', ' ', trim($item));
                                                $normalizedInputValue = preg_replace('/\s+/', ' ',
                                                trim(Helper::getInputValue($index, $data)));
                                                @endphp
                                                <option value="{{ $normalizedItem }}" {{
                                                    strcasecmp($normalizedInputValue, $normalizedItem)===0 ? 'selected'
                                                    : '' }}>
                                                    {{ $normalizedItem }}
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
                                            @php $index = 'registration_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Registration Number <span
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
                                            @php $index = 'unique_tax_reference_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Unique Tax Reference Number
                                                (UTR)</label>
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
                                            @php $index = 'monthly_turnover'; @endphp
                                            <label class="form-label" for="{{ $index }}">Current Monthly
                                                Turnover</label>
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
                                            @php $index = 'date_of_incorporation'; @endphp
                                            <label class="form-label" for="{{ $index }}">Date of Incorporation</label>
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
                                            @php $index = 'ProjectRef'; @endphp
                                            <label class="form-label" for="{{ $index }}">Project Ref </label>
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
                                            @php $index = 'DealDate'; @endphp
                                            <label class="form-label" for="{{ $index }}">Deal Date </label>
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
                                            $index = 'number_of_director';
                                            $number_of_director = DB::table('ga_credit_safe_directors')
                                            ->where('ga_credit_safe_id', $data->ga_credit_safe_id)
                                            ->count();
                                            $number_of_director = $number_of_director > 0 ? $number_of_director : 0;
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Number of Directors</label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ old($index, $number_of_director) }}" disabled>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">
                                                {{ $errors->first($index) }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'website_address'; @endphp
                                            <label class="form-label" for="{{ $index }}">Website Address </label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}"
                                                placeholder="https://abc.com">
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
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                name="{{ $index }}" id="{{ $index }}">
                                                <option value="">Select country</option>
                                                <option value="england" {{ Helper::getInputValue($index,
                                                    $data)=='england' ? 'selected' : '' }}>England</option>
                                                <option value="northern-ireland" {{ Helper::getInputValue($index,
                                                    $data)=='northern-ireland' ? 'selected' : '' }}>Northern Ireland
                                                </option>
                                                <option value="scotland" {{ Helper::getInputValue($index,
                                                    $data)=='scotland' ? 'selected' : '' }}>Scotland</option>
                                                <option value="wales" {{ Helper::getInputValue($index, $data)=='wales'
                                                    ? 'selected' : '' }}>Wales</option>
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
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'address'; @endphp
                                            <label class="form-label" for="{{ $index }}">Business Address </label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ $gaCreditSafe->compIdentiBasicInfo_contactAddress_simpleValue ?? '' }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            @php $index = 'city'; @endphp
                                            <label class="form-label" for="{{ $index }}">Town/City </label>
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
                                            @php $index = 'postal_code'; @endphp
                                            <label class="form-label" for="{{ $index }}">Postal Code </label>
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
                                            @php $index = 'switchboard_number'; @endphp
                                            <label class="form-label" for="{{ $index }}">Main Number </label>
                                            <input type="text"
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                value="{{ Helper::getInputValue($index, $data) }}">
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-3">
                                            @php $index = 'reason_for_funding'; @endphp
                                            <label class="form-label" for="{{ $index }}">Reason for Funding</label>
                                            <textarea
                                                class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                id="{{ $index }}" name="{{ $index }}"
                                                rows="5">{{ Helper::getInputValue($index, $data) }}</textarea>
                                            @if ($errors->has($index))
                                            <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card no-lt-rt-pad" x-data="{ 
                            checked_address: {{ json_encode((bool)$data->checked_address) }} 
                        }">
                            <div class="card-header">
                                <h4 class="header-title mb-0">Trading Address</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="cust_title">
                                    <div class="form-check form-checkbox-dark mb-2">
                                        @php $index = 'checked_address'; @endphp
                                        <input type="checkbox" class="form-check-input {{ $index }}" id="{{ $index }}"
                                            name="{{ $index }}" x-model="checked_address"
                                            @change="checked_address = $event.target.checked ? 'on' : ''">
                                        <label class="form-check-label" for="{{ $index }}">
                                            <p class="mb-0">Registered Address is the same as Trading Address</p>
                                        </label>
                                    </div>
                                </div>

                                <template x-if="!checked_address">
                                    <div class="row trading-address">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'trcountry'; @endphp
                                                <label class="form-label" for="{{ $index }}">Country </label>
                                                <select
                                                    class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }} {{ $index }}"
                                                    name="{{ $index }}" id="{{ $index }}" {{ $data->checked_address ==
                                                    true
                                                    ? 'disabled' : '' }}>
                                                    <option value="">Select country</option>
                                                    <option value="england" {{ Helper::getInputValue($index,
                                                        $data)=='england' ? 'selected' : '' }}>England</option>
                                                    <option value="northern-ireland" {{ Helper::getInputValue($index,
                                                        $data)=='northern-ireland' ? 'selected' : '' }}>Northern Ireland
                                                    </option>
                                                    <option value="scotland" {{ Helper::getInputValue($index,
                                                        $data)=='scotland' ? 'selected' : '' }}>Scotland</option>
                                                    <option value="wales" {{ Helper::getInputValue($index,
                                                        $data)=='wales' ? 'selected' : '' }}>Wales</option>
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
                                                <input type="text"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}">
                                                @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="mb-3">
                                                @php $index = 'traddress'; @endphp
                                                <label class="form-label" for="{{ $index }}">Business Address </label>
                                                <input type="text"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}" {{
                                                    $data->checked_address == true ? 'disabled' : '' }}>
                                                @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'trcity'; @endphp
                                                <label class="form-label" for="{{ $index }}">Town/City </label>
                                                <input type="text"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}" {{
                                                    $data->checked_address == true ? 'disabled' : '' }}>
                                                @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                @php $index = 'trpostal_code'; @endphp
                                                <label class="form-label" for="{{ $index }}">Postal Code </label>
                                                <input type="text"
                                                    class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                                    id="{{ $index }}" name="{{ $index }}"
                                                    value="{{ Helper::getInputValue($index, $data) }}" {{
                                                    $data->checked_address == true ? 'disabled' : '' }}>
                                                @if ($errors->has($index))
                                                <div class="invalid-feedback">{{ $errors->first($index) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
                            <a href="{{ route('businesses.business.info', $id) }}"
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
<script src="//unpkg.com/alpinejs" defer></script>
@endsection