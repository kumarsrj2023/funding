@extends('index')
@section('title', Helper::getSiteTitle('Director Info'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Director Info</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'Director Info' }}</h4>
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
                        {!! Helper::getDatatables(['Name', 'Address', 'Gender', 'Date Of Birth', 'Nationality',
                        'Occupation', 'Date Appointed']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@include('businesses.add.director-info.modals', ['id' => $id])
@endsection

@section('meta')
<meta name="remove-url" content="{{ route('businesses.director.info.remove') }}">
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
                                        <a href="{{ route('businesses.director.info.add', $id) }}" class="btn btn-sm btn-custom">Add New</a>
                                    </div>`;
                                    // <button type="button" class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#director-export">Export</button>
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
                        { className: "text-center", "targets": [ 3 ] },
                        { className: "text-center", "targets": [ 4 ] },
                        { className: "text-center", "targets": [ 5 ] },
                        { className: "text-left", "targets": [ 6 ] },
                        { className: "text-left", "targets": [ 7 ] },
                    ],
                    "columns":[
                        { "data": "actions", "name":"index_data" },
                        { "data": "name", "name":"name" },
                        { "data": "address_simple_value", "name":"address_simple_value" },
                        { "data": "gender", "name":"gender" },
                        { "data": "date_of_birth", "name":"date_of_birth" },
                        { "data": "nationality", "name":"nationality" },
                        { "data": "occupation", "name":"occupation" },
                        { "data": "date_appointed", "name":"date_appointed" },
                    ]
                });
    }
</script>
@endsection