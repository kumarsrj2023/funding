@extends('index')
@section('title', Helper::getSiteTitle('Transaction Form'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transaction Form</li>
                </ol>
            </div>
            <h4 class="page-title">Transaction Form</h4>
        </div>
    </div>
</div>
<div class="row list-page">
    <div class="col-12">
        <div class="card">
            <div class="card-body no-lt-rt-pad">
                {!! Helper::getDatatables(['Account Name', 'Amount', 'Date', 'Transaction'], 0) !!}
            </div>
        </div>
    </div>
</div>
@include('transaction-form.modals')
@endsection

@section('meta')
<meta name="class-to-open" content="transaction-form">
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
                    drawCallback: function() {
                        if($('.dt-search').find('.remove-all-button').length < 1)
                        {
                            $('.dt-search').prepend(`<button type="button" class="btn btn-sm btn-danger me-1 d-none remove-all-button"><i class="ri-delete-bin-5-line"></i></button>`);
                        }
                    },
                    "pageLength": 15,
                    "scrollX": false,
                    "ordering": false,
                    "lengthChange": true,
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
                                        <button type="button" class="btn btn-sm btn-custom" data-bs-toggle="modal" data-bs-target="#transaction-form-add">Add New</button>
                                    </div>`;
                        }
                    },
                    "ajax":
                    {
                        url: window.location.href,
                        data: function ( d ) {

                            if ($(".sorting-filter-menu").length)
                            {
                                d.sort_by = $(".sorting-filter-menu input:checked").val();
                            }

                        },
                        beforeSend: function(jqXHR) {},
                        error: function(jqXHR, textStatus, errorThrown){},
                        complete: function(jqXHR) {}
                    },
                    createdRow: function (row, data, dataIndex) {},
                    "columnDefs": [
                        { className: "text-left", "targets": [ 0 ] },
                        { className: "text-center", "targets": [ 1 ] },
                        { className: "text-center", "targets": [ 2 ] },
                        { className: "text-left", "targets": [ 3 ] },
                    ],
                    "columns":[
                        { "data": "account_name", "name":"account_name" },
                        { "data": "amount", "name":"amount" },
                        { "data": "Date", "name":"Date" },
                        { "data": "Transaction", "name":"Transaction" },
                    ]
                });
    }
</script>
@endsection

