@extends('index')
@section('title', Helper::getSiteTitle('Loan Info'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Loan Info</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ? $business_info->business_name : 'Loan Info' }}</h4>
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
                        {!! Helper::getDatatables(['Loan Number', 'Advance Requested', 'Loan Purpose', 'Deal Status', 'Funded Date']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('businesses.loan.info.remove') }}">
<meta name="class-to-open" content="businesses">
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
                                        <a href="{{ route('businesses.loan.info.add', $id) }}" class="btn btn-sm btn-custom">Add New</a>
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
                            $(row).addClass('row-url-redirect');
                            $(row).attr('data-rowurl', data.row_url);
                        }
                    },
                    "columnDefs": [
                        { className: "index-colum-checkbx no-rowurl-redirect", "targets": [ 0 ] },
                        { className: "text-left w-45", "targets": [ 1 ] },
                        { className: "text-left", "targets": [ 2 ] },
                        { className: "text-left", "targets": [ 3 ] },
                        { className: "text-left", "targets": [ 4 ] },
                        { className: "text-left", "targets": [ 5 ] },
                    ],
                    "columns":[
                        { "data": "actions", "name":"index_data" },
                        { "data": "loan_number", "name":"loan_number" },
                        { "data": "advance_requested", "name":"advance_requested" },
                        { "data": "loan_purpose", "name":"loan_purpose" },
                        { "data": "deal_status", "name":"deal_status" },
                        { "data": "funded_date", "name":"funded_date" },
                    ]
                });
    }
</script>
@endsection