@extends('index')
@section('title', Helper::getSiteTitle('Broker List'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Broker List</li>
                </ol>
            </div>
            <h4 class="page-title">Broker List</h4>
        </div>
    </div>
</div>
<div class="row list-page">
    <div class="col-12">
        <div class="card">
            <div class="card-body no-lt-rt-pad">
                {!! Helper::getDatatables(['Name', 'Email', 'Region', 'Phone', 'Business Name', 'Introducer', 'Note']) !!}
            </div>
        </div>
    </div>
</div>
@include('brokers.modals')
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('broker.list.remove') }}">
<meta name="class-to-open" content="brokers">
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
                                        <button type="button" class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#brokers-export">Export</button>
                                        <button type="button" class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#brokers-import">Import</button>
                                        <a href="{{ route('broker.list.add') }}" class="btn btn-sm btn-custom">Add New</a>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-light dropdown-toggle sorting-filter-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-arrow-up-down-line"></i>
                                            </button>
                                            <div class="dropdown-menu sorting-filter-menu" aria-labelledby="dropdownMenuButton">
                                                <div class="dropdown-item">
                                                    <p class="mb-0">Sort By</p>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio1" name="sort_by" value="broker_name-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio1">Broker Name (A to Z)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio2" name="sort_by" value="broker_name-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio2">Broker Name (Z to A)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio1" name="sort_by" value="business_name-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio1">Business Name (A to Z)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio2" name="sort_by" value="business_name-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio2">Business Name (Z to A)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio3" name="sort_by" value="introducer-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio3">Introducer (A to Z)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio4" name="sort_by" value="introducer-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio4">Introducer (Z to A)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                        { className: "text-center", "targets": [ 4 ] },
                        { className: "text-left", "targets": [ 5 ] },
                        { className: "text-left", "targets": [ 6 ] },
                        { className: "text-left", "targets": [ 7 ] },
                    ],
                    "columns":[
                        { "data": "actions", "name":"index_data" },
                        { "data": "broker_name", "name":"broker_name" },
                        { "data": "broker_email", "name":"broker_email" },
                        { "data": "region", "name":"region" },
                        { "data": "phone_no", "name":"phone_no" },
                        { "data": "business_name", "name":"business_name" },
                        { "data": "introducer", "name":"introducer" },
                        { "data": "broker_note", "name":"broker_note" },
                    ]
                });
    }
</script>
@endsection