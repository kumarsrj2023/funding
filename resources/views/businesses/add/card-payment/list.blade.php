@extends('index')
@section('title', Helper::getSiteTitle('Card Payments'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Card Payments</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Card Payments' }}</h4>
        </div>
    </div>
</div>
<div class="row ">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @include('businesses.add.nav', ['id' => $id])
                <div class="row">
                    <div class="card no-lt-rt-pad">
                        {!! Helper::getDatatables(['Type', 'Amount', 'No. of Payments', 'Interval', 'Time', 'Days of Week', 'Created At']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('businesses.add.card-payment.modals', ['id' => $id])
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('businesses.card.payment.remove') }}">
<meta name="class-to-open" content="businesses">
@endsection

@section('js-lib')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
@endsection

@section('css-lib')
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
                                        <button type="button" class="btn btn-sm btn-custom" data-bs-toggle="modal" data-bs-target="#payment-form-add">Add New</button>
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
                    createdRow: function (row, data, dataIndex) {
                        
                        if (data.row_url)
                        {
                            $(row).addClass('row-url-redirect fetch-dynamic-modal');
                            $(row).attr('data-rowurl', data.row_url);
                        }
                    },
                    "columnDefs": [
                        { className: "index-colum-checkbx no-rowurl-redirect", "targets": [ 0 ] },
                        { className: "text-left", "targets": [ 1 ] },
                        { className: "text-center", "targets": [ 2 ] },
                        { className: "text-center", "targets": [ 3 ] },
                        { className: "text-center", "targets": [ 4 ] },
                        { className: "text-center", "targets": [ 5 ] },
                        { className: "text-left", "targets": [ 6 ] },
                        { className: "text-right", "targets": [ 7 ] },
                    ],
                    "columns":[
                        { "data": "actions", "name":"index_data" },
                        { "data": "payment_type", "name":"payment_type" },
                        { "data": "amount", "name":"amount" },
                        { "data": "installments", "name":"installments" },
                        { "data": "recurring_payment", "name":"recurring_payment" },
                        { "data": "recurring_payment_time", "name":"recurring_payment_time" },
                        { "data": "recurring_payment_day_of_week", "name":"recurring_payment_day_of_week" },
                        { "data": "created_at", "name":"created_at" },
                    ]
                });
    }
</script>
@endsection