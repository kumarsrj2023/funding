@extends('index')
@section('title', Helper::getSiteTitle('Committee Paper'))

@section('page-css')
<style>
    .table-main-header{
        background-color: #003366 !important;
        color: white;
        font-size: 16px;
        padding: 1rem;
    }
    .table-section-header {
        background-color: #003366;
        color: white;
        text-align: center;
        font-weight: bold;
    }

    .input-field {
        width: 100%;
        border: none;
        outline: none;
        padding: 5px;
    }

    .text-label {
        font-weight: bold;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Committee Paper</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'Committee Paper' }}</h4>
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
                        <div class="col-12">
                            <h5 class="table-main-header mb-0 text-center text-center">SECTION ONE: Company and Application Details</h5>
                            <table class="table first-table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="table-section-header">Company Key Details</th>
                                        <th colspan="2" class="table-section-header">Application Key Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="text-label">Business Name</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Introduction</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Company Number</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Amount Funding</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Registered Address</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Servicing & Admin Fee</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Date Of Incorporation</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Multiple</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Website</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Total Repayable</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Shareholding & Directors</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Allocation Of The Funds</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Personal Guarantee(s)</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">IRR</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="4"><span class="text-label">How They Make Their Money</span></td>
                                        <td rowspan="4"><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Why IRR Chosen</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Duration</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">How Long Till Breakeven</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Rate Of Income</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><span class="text-label">Creditsafe Status</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Repayment Frequency</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Active CCJ’s</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Repayment Type</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Weighted Scorecard</span></td>
                                        <td><input type="text" class="input-field company-key-details" value=""></td>
                                        <td><span class="text-label">Fixed Repayment Amount</span></td>
                                        <td><input type="text" class="input-field app-key-details" value=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <h5 class="table-main-header mb-0 text-center text-center">SECTION TWO: Director and Homeownership</h5>
                            <table class="table second-table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="table-section-header">Director #1 Information</th>
                                        <th colspan="2" class="table-section-header">Director #2 Information</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="text-label">Full Name </span></td>
                                        <td><input type="text" class="input-field director-1" value=""></td>
                                        <td><span class="text-label">Full Name </span></td>
                                        <td><input type="text" class="input-field director-2" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Date Of Birth </span></td>
                                        <td><input type="text" class="input-field director-1" value=""></td>
                                        <td><span class="text-label">Date Of Birth </span></td>
                                        <td><input type="text" class="input-field director-2" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Nationality</span></td>
                                        <td><input type="text" class="input-field director-1" value=""></td>
                                        <td><span class="text-label">Nationality</span></td>
                                        <td><input type="text" class="input-field director-2" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">CIFAS Return</span></td>
                                        <td><input type="text" class="input-field director-1" value=""></td>
                                        <td><span class="text-label">CIFAS Return</span></td>
                                        <td><input type="text" class="input-field director-2" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Transunion</span></td>
                                        <td><input type="text" class="input-field director-1" value=""></td>
                                        <td><span class="text-label">Transunion</span></td>
                                        <td><input type="text" class="input-field director-2" value=""></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-label">Home Address</span></td>
                                        <td><input type="text" class="input-field director-1" value=""></td>
                                        <td><span class="text-label">Home Address</span></td>
                                        <td><input type="text" class="input-field director-2" value=""></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('css-lib')
<link rel="stylesheet" href="{{ asset('css/sop.css') }}">
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('js-lib')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
@endsection