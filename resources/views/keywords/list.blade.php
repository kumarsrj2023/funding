@extends('index')
@section('title', Helper::getSiteTitle('Keywords'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Keywords</li>
                </ol>
            </div>
            <h4 class="page-title">Keywords</h4>
        </div>
    </div>
</div>
<div class="row list-page">
    <div class="col-12">
        <div class="card">
            <div class="card-body no-lt-rt-pad">
                {!! Helper::getDatatables(['Search Term 1', 'Search Term 2', 'Excluding', 'Amount', 'Description', 'Description 2', 'Deal Specific']) !!}
            </div>
        </div>
    </div>
</div>
@include('keywords.modals')
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('keywords.remove') }}">
<meta name="class-to-open" content="keywords">
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

        var project_ref = '';

        @if(!$bank_statements->isEmpty())
            project_ref += '<select id="project_ref" name="project_ref" class="form-select form-select-sm me-1 search-in-datatables d-inline-block"><option value="">Select deal specific</option>';
            
            @foreach($bank_statements as $bank_statement)
                project_ref += '<option value="{{ trim($bank_statement->ProjectRef) }}">{{ trim($bank_statement->ProjectRef) }}</option>';
            @endforeach

            project_ref += '</select>';
        @endif

        var description = '';

        @if(!$keywords_sort_order->isEmpty())
            description += '<select id="description" name="description" class="form-select form-select-sm me-1 search-in-datatables d-inline-block"><option value="">Select description</option>';
            
            @foreach($keywords_sort_order as $keyword_sort_order)
                description += '<option value="{{ trim($keyword_sort_order->Field) }}">{{ trim($keyword_sort_order->Field) }}</option>';
            @endforeach

            description += '</select>';
        @endif

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
                                        `+ description +`
                                        `+ project_ref +`
                                        <button type="button" class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#keywords-export">Export</button>
                                        <button type="button" class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#keywords-import">Import</button>
                                        <a href="{{ route('keywords.add') }}" class="btn btn-sm btn-custom">Add New</a>
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
                                                        <input type="radio" id="customRadio1" name="sort_by" value="SearchTerm1-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio1">Search Term 1 (A to Z)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio2" name="sort_by" value="SearchTerm1-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio2">Search Term 1 (Z to A)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio1" name="sort_by" value="SearchTerm2-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio1">Search Term 2 (A to Z)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio2" name="sort_by" value="SearchTerm2-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio2">Search Term 2 (Z to A)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio3" name="sort_by" value="Excluding-asc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio3">Excluding (A to Z)</label>
                                                    </div>
                                                </div>
                                                <div class="dropdown-item">
                                                    <div class="form-check form-checkbox-dark">
                                                        <input type="radio" id="customRadio4" name="sort_by" value="Excluding-desc" class="form-check-input">
                                                        <label class="form-check-label" for="customRadio4">Excluding (Z to A)</label>
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

                            if ($("#project_ref").length)
                            {
                                d.project_ref = $("#project_ref").val();
                            }

                            if ($("#description").length)
                            {
                                d.description = $("#description").val();
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
                        { "data": "SearchTerm1", "name":"SearchTerm1" },
                        { "data": "SearchTerm2", "name":"SearchTerm2" },
                        { "data": "Excluding", "name":"Excluding" },
                        { "data": "Amount", "name":"Amount" },
                        { "data": "Description", "name":"Description" },
                        { "data": "Description2", "name":"Description2" },
                        { "data": "DealSpecific", "name":"DealSpecific" },
                    ]
                });
    }
</script>
@endsection