@extends('index')
@section('title', Helper::getSiteTitle('Open Banking Accounts'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Open Banking Accounts</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Open Banking Accounts' }}</h4>
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
                                <h4 class="header-title mb-0">Open Banking Accounts</h4>
                            </div>
                            <div class="card-body compact-card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="ga-open-banking-accounts mb-3">
                                            <table class="table cust_info w-100">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Account</th>
                                                        <th class="text-right w-100px" scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="repeater ob-repeater">
                                                    @if(!$open_banking_details->isEmpty())
                                                        @foreach($open_banking_details as $open_banking_detail)
                                                            <tr class="node">
                                                                <td class="no-lt-rt-pad">
                                                                    <select class="form-select w-100" name="open_banking_account_id[]">
                                                                        <option value="">Select Account</option>
                                                                        @foreach($ob_accounts as $account)
                                                                            <option value="{{ $account->id }}" {{ isset($open_banking_detail->tbl_account_id) && $open_banking_detail->tbl_account_id == $account->id ? 'selected' : '' }}>{{ $account->id }} - {{ $account->mask }} - {{ $account->name }} ({{ $account->email }})</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-right w-100px">
                                                                    <button type="button" class="btn btn-sm btn-danger me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="node">
                                                            <td class="no-lt-rt-pad">
                                                                <select class="form-select w-100" name="open_banking_account_id[]">
                                                                    <option value="">Select Account</option>
                                                                    @if(!$ob_accounts->isEmpty())
                                                                        @foreach($ob_accounts as $account)
                                                                            <option value="{{ $account->id }}">{{ $account->id }} - {{ $account->mask }} - {{ $account->name }} ({{ $account->email }})</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td class="text-right w-100px">
                                                                <button type="button" class="btn btn-sm btn-danger me-1 delete-repeater-node"><i class="ri-delete-bin-5-line"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                            <a href="javascript:void(0)" class="insert-repeater text-decoration-underline" data-repeaterclass="ob-repeater">Add New Account</a>
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