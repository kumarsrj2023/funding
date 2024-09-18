@extends('index')
@section('title', Helper::getSiteTitle('CAIS File'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">CAIS File</li>
                </ol>
            </div>
            <h4 class="page-title">CAIS File</h4>
        </div>
    </div>
</div>
<div class="row list-page">
    <div class="col-12">
        <div class="card">
            <div class="card-body no-lt-rt-pad">
                {!! Helper::getDatatables(['#', 'Business Name', 'Loan Number', 'Name Change', 'Previous Name And Address', 'Special Instruction Indicator', 'Flag Settings', 'Mortgage Flags', 'Transfered To Collection Account', 'Default Satisfaction Date', 'Original Default Balance', 'New Account Number', 'New Person Number'], 0, '') !!}
            </div>
        </div>
    </div>
</div>
@include('cais.modals')
@endsection

@section('meta')
<meta name="class-to-open" content="cais-file">
<meta name="cais-action-url" content="{{ route('cais.file.action') }}">
<meta name="cis-value-update-url" content="{{ route('cais.file.update.values') }}">
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
                    "pageLength": 10,
                    "lengthChange": false,
                    "paging": true,
                    "searching": true,
                    "responsive": true,
                    "processing": true,
                    "serverSide": true,
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
                                        <select id="current_action" name="current_action" class="form-select cais-action form-select-sm me-1 d-inline-block">
                                            <option value="">Bulk Actions</option>
                                            <option value="get-credit-safe-details" data-icon="info" data-title="Update Credit Safe Details" data-text="It will update the credit safe details of all selected businesses" data-confirmbuttontext="Update">Update Credit Safe Details</option>
                                            <option value="export-csv" data-icon="info" data-title="Export CSV File of CAIS" data-text="It will export cais data of all selected businesses in csv file" data-confirmbuttontext="Export">Export CSV File of CAIS</option>
                                            <option value="export-txt" data-icon="info" data-title="Export CAIS - Equifax" data-text="It will export equifax cais data of all selected businesses in text file" data-confirmbuttontext="Export">Export CAIS - Equifax</option>
                                            <option value="export-txt-experian" data-icon="info" data-title="Export CAIS - Experian" data-text="It will export experian cais data of all selected businesses in text file" data-confirmbuttontext="Export">Export CAIS - Experian</option>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-custom" data-bs-toggle="modal" data-bs-target="#cais-form-add">Add Business</button>
                                    </div>`;
                        }
                    },
                    "ajax":
                    {
                        url: window.location.href,
                        data: function ( d ) {},
                        beforeSend: function(jqXHR) {},
                        error: function(jqXHR, textStatus, errorThrown){},
                        complete: function(jqXHR) {}
                    },
                    createdRow: function (row, data, dataIndex) {
                        
                        if (data.row_url)
                        {
                            $(row).addClass('row-url-redirect');
                            $(row).attr('data-rowurl', data.row_url);
                        }

                        $(row).addClass('cais-tr');
                        $(row).attr('data-id', data.id);
                    },
                    "columnDefs": [
                        { className: "index-colum-checkbx no-rowurl-redirect", "targets": [ 0 ] },
                        { className: "text-left w-45", "targets": [ 1 ] },
                        { className: "text-left", "targets": [ 2 ] },
                        { className: "text-left", "targets": [ 3 ] },
                        { className: "text-left", "targets": [ 4 ] },
                        { className: "text-left", "targets": [ 5 ] },
                        { className: "text-left", "targets": [ 6 ] },
                        { className: "text-left", "targets": [ 7 ] },
                        { className: "text-left", "targets": [ 8 ] },
                        { className: "text-left", "targets": [ 9 ] },
                        { className: "text-left", "targets": [ 10 ] },
                        { className: "text-left", "targets": [ 11 ] },
                        { className: "text-left ", "targets": [ 12 ] },
                    ],
                    "columns":[
                        { "data": "index_data", "name":"index_data" },
                        { "data": "business_name", "name":"wp_business_info.business_name" },
                        { "data": "loan_number", "name":"wp_loan_info.loan_number" },
                        { "data": "name_change", "name":"index_data" },
                        { "data": "previous_name_and_address", "name":"index_data" },
                        { "data": "special_instruction_indicator", "name":"index_data" },
                        { "data": "flag_settings", "name":"index_data" },
                        { "data": "mortgage_flags", "name":"index_data" },
                        { "data": "transfered_to_collection_account", "name":"index_data" },
                        { "data": "default_satisfaction_date", "name":"index_data" },
                        { "data": "original_default_balance", "name":"index_data" },
                        { "data": "new_account_number", "name":"wp_business_info.new_account_number" },
                        { "data": "new_person_number", "name":"wp_business_info.new_person_number" },
                    ]
                });
    }
</script>
@endsection