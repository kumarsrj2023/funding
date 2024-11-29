@extends('index')
@section('title', Helper::getSiteTitle('SIF Funding Checklist'))

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">SIF Funding Checklist</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'SIF Funding Checklist' }}</h4>
        </div>
    </div>
</div>
<div class="form-wrapper funding-checklist">
    <form id="fundingChecklist" action="{{ route('businesses.save.siffunding.checklist', $id) }}" method="POST">
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
                                @php
                                $table_name = "section-invoice";
                                $helper_class = "invoice";
                                $rowspanStructure = [];
                                $hiddenRows = [];
                                @endphp
                                <div class="table-responsive">
                                    <table class="table table-bordered {{ $table_name }}">
                                        <thead class="thead-dark">
                                            <tr class="table-main-header mb-0 text-center text-center">
                                                <th colspan="3" class="text-center">Pre-Sending Out The Agreement
                                                    Checklist
                                                </th>
                                                <th colspan="2" class="text-center">Second Checker (Other Underwriter)
                                                </th>
                                            </tr>
                                            <tr class="table-sub-header">
                                                <th></th>
                                                <th>Section 1 - Invoice</th>
                                                <th>Completed</th>
                                                <th>Completed</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody x-data="rowHandler('invoice', {{ json_encode($rowspanStructure) }})">
                                            @foreach($invoiceItems as $index => $item)
                                            <tr>
                                                <td>
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="checklist-column column-2">
                                                    <input type="hidden" name="invoice_items[{{ $index }}][invoice]"
                                                        value="{{ is_array($item) ? ($item['invoice'] ?? '') : ($item->invoice ?? '') }}"
                                                        class="{{ $helper_class }}_section select_field" readonly>

                                                    <div class="display-item">
                                                        {{ is_array($item) ? ($item['invoice'] ?? '') :
                                                        ($item->invoice ?? '') }}
                                                    </div>
                                                </td>

                                                @if(!in_array($index, $hiddenRows))
                                                <td @if(isset($rowspanStructure[$index]))
                                                    rowspan="{{ $rowspanStructure[$index] }}" @endif
                                                    class="completed-column column-3 align-middle">
                                                    <select name="invoice_items[{{ $index }}][completed]"
                                                        class="form-control {{ $helper_class }}_completed input-field"
                                                        x-model="fields[{{ $index }}].completed"
                                                        @change="updateHiddenFields({{ $index }}, 'completed', $event.target.value); updateSelectColor($event)">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </td>

                                                <td @if(isset($rowspanStructure[$index]))
                                                    rowspan="{{ $rowspanStructure[$index] }}" @endif
                                                    class="second-checker-completed-column column-4 align-middle">
                                                    <select
                                                        name="invoice_items[{{ $index }}][second_checker_completed]"
                                                        class="form-control {{ $helper_class }}_second_checker_completed select_field"
                                                        x-model="fields[{{ $index }}].second_checker_completed"
                                                        @change="updateHiddenFields({{ $index }}, 'second_checker_completed', $event.target.value); updateSelectColor($event)">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </td>
                                                @else
                                                <input type="hidden" name="invoice_items[{{ $index }}][completed]"
                                                    x-model="fields[{{ $index }}].completed"
                                                    id="completed-hidden-{{ $index }}">
                                                <input type="hidden"
                                                    name="invoice_items[{{ $index }}][second_checker_completed]"
                                                    x-model="fields[{{ $index }}].second_checker_completed"
                                                    id="second-checker-completed-hidden-{{ $index }}">
                                                @endif

                                                <td class="notes-column column-5">
                                                    <input type="text" name="invoice_items[{{ $index }}][notes]"
                                                        value="{{ is_array($item) ? ($item['notes'] ?? '') : ($item->notes ?? '') }}"
                                                        class="{{ $helper_class }}_notes input-field">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Section-2 -->
                            <div class="col-12">
                                @php
                                $table_name = "section-client";
                                $helper_class = "client";
                                $rowspanStructure = [];
                                $hiddenFields = [];
                                @endphp
                                <div class="table-responsive">
                                    <table class="table table-bordered {{ $table_name }}">
                                        <thead class="thead-dark">
                                            <tr class="table-sub-header">
                                                <th></th>
                                                <th>Section 2 - Client</th>
                                                <th>Completed</th>
                                                <th>Completed</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            x-data="rowHandler('client', {{ json_encode($rowspanStructure) }})">
                                            @foreach($clientItems as $index => $item)
                                            <tr>
                                                <td>
                                                    {{ $index + 1 }}
                                                </td>

                                                <td class="agreement-column column-2">
                                                    <input type="hidden"
                                                        name="client_items[{{ $index }}][client]"
                                                        value="{{ is_array($item) ? ($item['client'] ?? '') : ($item->client ?? '') }}"
                                                        class="{{ $helper_class }}_section">

                                                    <span class="display-item">
                                                        {{ is_array($item) ? ($item['client'] ?? '')
                                                        : ($item->client ?? '') }}
                                                    </span>
                                                </td>

                                                @if(!in_array($index, $hiddenFields))
                                                <td class="completed-column column-3 align-middle"
                                                    @if(isset($rowspanStructure[$index]))
                                                    rowspan="{{ $rowspanStructure[$index] }}" @endif>
                                                    <select name="client_items[{{ $index }}][completed]"
                                                        class="form-control {{ $helper_class }}_completed input-field"
                                                        x-model="fields[{{ $index }}].completed"
                                                        @change="updateHiddenFields({{ $index }}, 'completed', $event.target.value); updateSelectColor($event)">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </td>
                                                <td class="second-checker-completed-column column-4 align-middle"
                                                    @if(isset($rowspanStructure[$index]))
                                                    rowspan="{{ $rowspanStructure[$index] }}" @endif>
                                                    <select
                                                        name="client_items[{{ $index }}][second_checker_completed]"
                                                        class="form-control {{ $helper_class }}_second_checker_completed select_field"
                                                        x-model="fields[{{ $index }}].second_checker_completed"
                                                        @change="updateHiddenFields({{ $index }}, 'second_checker_completed', $event.target.value); updateSelectColor($event)">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </td>
                                                @else
                                                <input type="hidden" name="client_items[{{ $index }}][completed]"
                                                    x-model="fields[{{ $index }}].completed"
                                                    id="completed-hidden-{{ $index }}">
                                                <input type="hidden"
                                                    name="client_items[{{ $index }}][second_checker_completed]"
                                                    x-model="fields[{{ $index }}].second_checker_completed"
                                                    id="second-checker-completed-hidden-{{ $index }}">
                                                @endif

                                                <td class="notes-column column-5">
                                                    <input type="text" name="client_items[{{ $index }}][notes]"
                                                        value="{{ is_array($item) ? ($item['notes'] ?? '') : ($item->notes ?? '') }}"
                                                        class="{{ $helper_class }}_notes input-field">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- To complete Once funded -->
                            <div class="col-12">
                                @php
                                $table_name = "verification-and-Completion";
                                $helper_class = "verification-and-Completion";
                                $rowspanStructure = [];
                                $hiddenRows = [];
                                @endphp
                                <div class="table-responsive">
                                    <table class="table table-bordered {{ $table_name }}">
                                        <thead class="thead-dark">
                                            <tr class="table-sub-header">
                                                <th></th>
                                                <th>Section 3 - Verification & Completion</th>
                                                <th>Completed</th>
                                                <th>Completed</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody x-data="rowHandler('verification_and_completion', {{ json_encode($rowspanStructure) }})">
                                            @foreach($verificationAndCompletion as $index => $item)
                                            <tr>
                                                <td>
                                                    {{ $index + 1 }}
                                                </td>

                                                <td class="agreement-column column-2">
                                                    <input type="hidden"
                                                        name="verification_and_completion_items[{{ $index }}][verification_and_completion]"
                                                        value="{{ is_array($item) ? ($item['verification_and_completion'] ?? '') : ($item->verification_and_completion ?? '') }}"
                                                        class="{{ $helper_class }}_section select_field"
                                                        readonly>

                                                    <span class="display-item">
                                                        {{ is_array($item) ? ($item['verification_and_completion'] ??
                                                        '') : ($item->verification_and_completion ?? '') }}
                                                    </span>
                                                </td>

                                                @if(!in_array($index, $hiddenRows))
                                                <td @if(isset($rowspanStructure[$index]))
                                                    rowspan="{{ $rowspanStructure[$index] }}" @endif
                                                    class="completed-column column-3 align-middle">
                                                    <select name="verification_and_completion_items[{{ $index }}][completed]"
                                                        class="form-control {{ $helper_class }}_completed input-field"
                                                        x-model="fields[{{ $index }}].completed"
                                                        @change="updateHiddenFields({{ $index }}, 'completed', $event.target.value); updateSelectColor($event)">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </td>
                                                <td @if(isset($rowspanStructure[$index]))
                                                    rowspan="{{ $rowspanStructure[$index] }}" @endif
                                                    class="second-checker-completed-column column-4 align-middle">
                                                    <select
                                                        name="verification_and_completion_items[{{ $index }}][second_checker_completed]"
                                                        class="form-control {{ $helper_class }}_second_checker_completed select_field"
                                                        x-model="fields[{{ $index }}].second_checker_completed"
                                                        @change="updateHiddenFields({{ $index }}, 'second_checker_completed', $event.target.value), updateSelectColor($event)">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </td>
                                                @else
                                                <input type="hidden" name="verification_and_completion_items[{{ $index }}][completed]"
                                                    x-model="fields[{{ $index }}].completed"
                                                    id="completed-hidden-{{ $index }}">
                                                <input type="hidden"
                                                    name="verification_and_completion_items[{{ $index }}][second_checker_completed]"
                                                    x-model="fields[{{ $index }}].second_checker_completed"
                                                    id="second-checker-completed-hidden-{{ $index }}">
                                                @endif

                                                <td class="notes-column column-5">
                                                    <input type="text" name="verification_and_completion_items[{{ $index }}][notes]"
                                                        value="{{ is_array($item) ? ($item['notes'] ?? '') : ($item->notes ?? '') }}"
                                                        class="{{ $helper_class }}_notes input-field">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn btn-custom me-1" type="submit" disabled>
                                    <span class="default-show">Save</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')

<script>
    function rowHandler(tableType, rowspanStructure) {
        return {
            fields: (() => {
                let fields = {};
                let items;
                switch(tableType) {
                    case 'invoice':
                        items = @json($invoiceItems);
                        break;
                    case 'client':
                        items = @json($clientItems);
                        break;
                    case 'verification_and_completion':
                        items = @json($verificationAndCompletion);
                        break;
                }
                Object.keys(items).forEach(index => {
                    fields[index] = {
                        completed: items[index].completed || 'No',
                        second_checker_completed: items[index].second_checker_completed || 'No'
                    };
                });
                return fields;
            })(),
            rowspanStructure: (() => {
                const convertRowspanStructure = (structure) => {
                    let result = {};
                    for (let parentIndex in structure) {
                        let span = structure[parentIndex];
                        result[parentIndex] = Array.from(
                            { length: span - 1 },
                            (_, i) => parseInt(parentIndex) + i + 1
                        );
                    }
                    return result;
                };

                return convertRowspanStructure(rowspanStructure);
            })(),
            updateHiddenFields(parentIndex, field, value) {
                if (this.rowspanStructure[parentIndex]) {
                    this.rowspanStructure[parentIndex].forEach(hiddenIndex => {
                        this.fields[hiddenIndex][field] = value;
                    });
                }
            },
            updateSelectColor(event) {
                const select = event.target;
                if (select.value === 'Yes') {
                    select.classList.remove('bg-red');
                    select.classList.add('bg-green');
                } else {
                    select.classList.remove('bg-green');
                    select.classList.add('bg-red');
                }
            },
            init() {
                this.$nextTick(() => {
                this.$el.querySelectorAll('select[name$="[completed]"], select[name$="[second_checker_completed]"]').forEach(select => {
                        this.updateSelectColor({ target: select });
                    });
                });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('fundingChecklist');
        // encable submit type button
        // form.querySelector([input="type=submit"]).disabled = false

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Download Word Document?',
                text: "Would you like to download the submitted data as a Word document?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, download it!',
                cancelButtonText: 'No, just submit',
            }).then((result) => {
                if (result.isConfirmed) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'download_doc';
                    input.value = '1';
                    form.appendChild(input);
                    form.submit();
                    form.removeChild(input);
                } else {
                    form.submit();
                }
            });
        });
    });

</script>


@endsection

@section('meta')
<meta name="class-to-open" content="businesses">
@endsection

@section('css-lib')
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/sop.css') }}">
@endsection

@section('js-lib')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
@endsection