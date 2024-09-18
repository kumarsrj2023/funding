@extends('index')
@section('title', Helper::getSiteTitle('Director Search'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Director Search</li>
                </ol>
            </div>
            <h4 class="page-title">Director Search</h4>
        </div>
    </div>
</div>
<form action="{{ route('director.search') }}" method="get">
    <div class="row">
        @include('includes.show-message', ['extra_class' => 'col-12 mb-2'])
        <div class="col-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row gy-2 gx-2 align-items-center">
                                <div class="col-auto">
                                    @php $index = 'fname'; @endphp
                                    <label class="form-label" for="{{ $index }}">First Name</label>
                                    <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, request()) }}">
                                </div>
                                <div class="col-auto">
                                    @php $index = 'lname'; @endphp
                                    <label class="form-label" for="{{ $index }}">Last Name</label>
                                    <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, request()) }}">
                                </div>
                                <div class="col-auto">
                                    @php $index = 'dob'; @endphp
                                    <label class="form-label" for="{{ $index }}">Date of Birth</label>
                                    <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" placeholder="YYYY-MM" value="{{ Helper::getInputValue($index, request()) }}">
                                </div>
                                <div class="col-auto">
                                    @php $index = 'country'; @endphp
                                    <label class="form-label" for="{{ $index }}">Country</label>
                                    <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="GB" value="{{ Helper::getInputValue($index, request()) }}">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-custom btn-show-processing mt-3" type="submit">
                                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                                        <span class="processing-show d-none">Searching...</span>
                                        <span class="default-show">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!empty($directors))
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table w-100 datatable nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <div class="form-check form-checkbox-dark">
                                                    <input type="checkbox" class="form-check-input select-all-checkbox" id="select-all-checkbox">
                                                    <label class="form-check-label" for="select-all-checkbox">&nbsp;</label>
                                                </div>
                                            </th>
                                            <th scope="col">First Name</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">Company</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Town/City</th>
                                            <th scope="col">Post Code</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Date of Birth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($directors as $key => $data)
                                            @php
                                                $directr_coompany_details = Helper::getDirectorDetails($data['peopleId']);
                                            @endphp
                                            @if (!empty($directr_coompany_details) && isset($directr_coompany_details['directorships']) && !empty($directr_coompany_details['directorships']))
                                                @foreach ($directr_coompany_details['directorships'] as $key => $directorships)
                                                    @foreach ($directorships as $key => $comp)
                                                        <tr>
                                                            <td class="text-left">
                                                                <div class="form-check form-checkbox-dark">
                                                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-{{ $data['peopleId'] }}" value="{{ $data['peopleId'] }}">
                                                                    <label class="form-check-label no-rowurl-redirect" for="select-item-{{ $data['peopleId'] }}">&nbsp;</label>
                                                                </div>
                                                            </td>
                                                            <td>{{ isset($data['firstName']) && !empty($data['firstName']) ? $data['firstName'] : '-' }}</td>
                                                            <td>{{ isset($data['lastName']) && !empty($data['lastName']) ? $data['lastName'] : '-' }}</td>
                                                            <td>{{ isset($comp['companyName']) && !empty($comp['companyName']) ? $comp['companyName'] : '-' }}</td>
                                                            <td>{{ isset($data['address']['simpleValue']) && !empty($data['address']['simpleValue']) ? $data['address']['simpleValue'] : '-' }}</td>
                                                            <td>{{ isset($data['address']['city']) && !empty($data['address']['city']) ? $data['address']['city'] : '-' }}</td>
                                                            <td>{{ isset($data['address']['postCode']) && !empty($data['address']['postCode']) ? $data['address']['postCode'] : '-' }}</td>
                                                            <td>{{ isset($data['status']) && !empty($data['status']) ? strtoupper($data['status']) : '-' }}</td>
                                                            <td>{{ isset($data['dateOfBirth']) && !empty($data['dateOfBirth']) ? date('d/m/Y', strtotime($data['dateOfBirth'])) : '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection

@section('meta')
<meta name="class-to-open" content="director-search">
@endsection

@section('js-lib')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
@endsection

@section('css-lib')
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
<script type="text/javascript">

    function createTable()
    {
        if (!$('.datatable').length)
        {
            return;
        }

        table = $('.datatable').DataTable({
                    "scrollX": false,
                    "ordering": false,
                    "pageLength": 150000,
                    "lengthChange": false,
                    "searching": true,
                    "language": {
                        "emptyTable": "{{ __('No result found') }}",
                        "search": ''
                    },
                    "layout": {
                        topStart: {
                            search: {
                                placeholder: 'Search...'
                            }
                        },
                        topEnd: function () {
                            return `<div class="dt-btns">
                                        <button type="button" class="btn btn-sm btn-dark">Get Company Details</button>
                                    </div>`;
                        }
                    }
                });
    }
</script>
@endsection