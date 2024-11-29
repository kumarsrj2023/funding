@extends('guest')
@section('title', Helper::getSiteTitle('Statement of Position'))


@section('content')

<div class="container sop" id="sop" x-data="sop(
    {saveAIPData: '',currentUrl: '{{ URL::current() }}',sopPdf: '{{ route('download.sop', $id) }}'},
    { 
        assetInfo: {{ json_encode($assetInfo, JSON_HEX_APOS) }},
        liabilitiesInfo: {{ json_encode($liabilitiesInfo, JSON_HEX_APOS) }},
        directorInfo: {{ json_encode($directorInfo, JSON_HEX_APOS) }}
    }
    )">
    <div class="response mt-3">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success_message'))
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
        @endif
    </div>
    <div x-show="loadingStates.saveData || loadingStates.exportPDF">
        <x-loader />
    </div>

    <form id="sopForm" action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
        @csrf
        <div id="content" class="" style="position: relative;">
            <div class="mb-1">
                <p class="header-desc">ALL INFORMATION IS HELD IN STRICTEST CONFIDENCE IN ACCORDANCE WITH OUR General
                    Data
                    Protection Regulation (GDPR) POLICY.</p>

                <div class="form-section">
                    <h2>Full Company Business Name and Registration Number</h2>
                    <div class="form-group">
                        @php $index = 'company_details'; @endphp
                        <input type="text"
                            class="{{ $index }}_business_name {{ $errors->has($index . '_business_name') ? 'is-invalid' : '' }}"
                            name="{{ $index }}_business_name"
                            value="{{ old($index . '_business_name', $businessInfo->business_name ?? '') }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Name and other details -->
            <div class="name-and-other-details  mb-1">
                <h2>Name and Other Details of Proposed Guarantor</h2>
                @php $index = 'proposed_guarantor_details'; @endphp
                <table class="table_{{ $index }}">
                    <tr>
                        <td class="td-label width-60">Title</td>
                        <td class="td-value">
                            <input type="text" name="{{ $index }}_title" x-model="directorInfo.title">
                        </td>
                        <td class="td-label width-110">First Name</td>
                        <td class="td-value">
                            <input type="text"
                                class="{{ $index }}_first_name {{ $errors->has($index . '_first_name') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_first_name" x-model="directorInfo.first_name">
                        </td>
                        <td class="td-label width-110">Middle Name</td>
                        <td class="td-value">
                            <input type="text"
                                class="{{ $index }}_middle_name {{ $errors->has($index . '_middle_name') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_middle_name" x-model="directorInfo.middle_name">
                        </td>
                        <td class="td-label width-110">Last Name</td>
                        <td class="td-value">
                            <input type="text"
                                class="{{ $index }}_surname {{ $errors->has($index . '_surname') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_surname" x-model="directorInfo.surname">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="td-label">Home Address</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">House/Flat No.</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_house_number {{ $errors->has($index . '_house_number') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_house_number"
                                value="{{ old($index . '_house_number', $directorInfo->house_number ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">Address Line 1</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_address_line_1 {{ $errors->has($index . '_address_line_1') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_address_line_1"
                                value="{{ old($index . '_address_line_1', $directorInfo->address_line_1 ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">Address Line 2</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_address_line_2 {{ $errors->has($index . '_address_line_2') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_address_line_2"
                                value="{{ old($index . '_address_line_2', $directorInfo->address_line_2 ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">Address Line 3</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_address_line_3 {{ $errors->has($index . '_address_line_3') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_address_line_3"
                                value="{{ old($index . '_address_line_3', $directorInfo->address_line_3 ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">Postal Code</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_address_postal_code {{ $errors->has($index . '_address_postal_code') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_address_postal_code"
                                value="{{ old($index . '_address_postal_code', $directorInfo->address_postal_code ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">Time in current address (months)</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_time_in_curr_address {{ $errors->has($index . '_time_in_curr_address') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_time_in_curr_address"
                                value="{{ old($index . '_time_in_curr_address', $directorInfo->time_in_curr_address ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="td-label width-110">Date of Birth</td>
                        <td class="td-value" colspan="2">
                            <input type="date"
                                class="{{ $index }}_date_of_birth {{ $errors->has($index . '_date_of_birth') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_date_of_birth"
                                value="{{ old($index . '_date_of_birth', $directorInfo->date_of_birth ?? '') }}">
                        </td>
                        <td class="td-label width-110">Email</td>
                        <td class="td-value" colspan="3">
                            <input type="email"
                                class="{{ $index }}_email {{ $errors->has($index . '_email') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_email"
                                value="{{ old($index . '_email', $directorInfo->email ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="td-label width-110">Tel Home</td>
                        <td class="td-value" colspan="2">
                            <input type="text"
                                class="{{ $index }}_tel_home {{ $errors->has($index . '_tel_home') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_tel_home"
                                value="{{ old($index . '_tel_home', $directorInfo->tel_home ?? '') }}">
                        </td>
                        <td class="td-label width-110">Tel Business</td>
                        <td class="td-value">
                            <input type="text"
                                class="{{ $index }}_tel_business {{ $errors->has($index . '_tel_business') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_tel_business"
                                value="{{ old($index . '_tel_business', $directorInfo->tel_business ?? '') }}">
                        </td>
                        <td class="td-label width-110">Mobile</td>
                        <td class="td-value">
                            <input type="tel"
                                class="{{ $index }}_mobile {{ $errors->has($index . '_mobile') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_mobile"
                                value="{{ old($index . '_mobile', $directorInfo->mobile ?? '') }}">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="td-label width-110">Are You or Have You Ever Been Declared Bankrupt?
                            (Yes/No)
                        </td>
                        <td class="td-value" colspan="2">
                            <select
                                class="w-100 border-0 outline-none {{ $index }}_declared_bankrupt {{ $errors->has($index . '_declared_bankrupt') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_declared_bankrupt">
                                <option value="n" {{ old($index . '_declared_bankrupt' , $directorInfo->
                                    declared_bankrupt ?? '') == 'n' ? 'selected' : '' }}>No</option>
                                <option value="y" {{ old($index . '_declared_bankrupt' , $directorInfo->
                                    declared_bankrupt ?? '') == 'y' ? 'selected' : '' }}>Yes</option>
                            </select>

                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" class="td-label width-110">Next of Kin Full Name</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_next_of_kin_full_name {{ $errors->has($index . '_next_of_kin_full_name') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_next_of_kin_full_name"
                                value="{{ old($index . '_next_of_kin_full_name', $directorInfo->next_of_kin_full_name ?? '') }}">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" class="td-label width-110">Next of Kin Mobile Number</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_next_of_kin_mobile_number {{ $errors->has($index . '_next_of_kin_mobile_number') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_next_of_kin_mobile_number"
                                value="{{ old($index . '_next_of_kin_mobile_number', $directorInfo->next_of_kin_mobile_number ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">Next of Kin Email Address</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_next_of_kin_email_address {{ $errors->has($index . '_next_of_kin_email_address') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_next_of_kin_email_address"
                                value="{{ old($index . '_next_of_kin_email_address', $directorInfo->next_of_kin_email_address ?? '') }}">
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Assets Liabilities -->
            <div class="assets-liabilities border-0 ">
                <h2>Assets and Liabilities</h2>

                <div class="content">
                    <p>Please provide a full list of assets whether held in your name or otherwise and liabilities. If
                        you need
                        more space for details please set out on a separate sheet.</p>
                    <div class="checkbox-group">
                        @php $index = 'assets-liabilities'; @endphp
                        <input type="checkbox"
                            class="w-auto {{ $index }}_terms {{ $errors->has($index . '_terms') ? 'is-invalid' : '' }}"
                            name="{{ $index }}_terms" value="1" checked>



                        <label for="assets_trust">Tick box if any of these assets are owned by the proposed
                            guarantor/individual
                            as trustee of a trust for someone else or are owned by others but the others hold the asset
                            as
                            trustee for the benefit of the individual proposed Guarantor or are in the name of a spouse
                            or any
                            other person/entity. If so please specify which asset(s) and identify the third parties
                            below.</label>
                    </div>
                </div>
            </div>

            <!-- Assets and Liabilities Details -->
            <div class="assets-details">
                @php
                $assets = 'assets';
                $liabilities = 'liabilities';
                @endphp
                <table class="table-collapse">
                    <thead>
                        <tr>
                            <th colspan="4" class="t-head">
                                Assets
                            </th>
                            <th colspan="4" class="t-head">
                                Liabilities
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th colspan="2" class="body-t-head">
                                Where appropriate please provide account/registration numbers
                            </th>
                            <td colspan="2" class="body-t-value">
                                <input type="text" class="{{ $assets }}_account_or_regnumber"
                                    name="{{ $assets }}_account_or_regnumber" x-model="assetInfo.account_or_regnumber">
                            </td>
                            <th colspan="2" class="body-t-head">
                                Where appropriate please provide account/registration numbers
                            </th>
                            <td colspan="2" class="body-t-value">
                                <input type="text" class="{{ $liabilities }}_account_or_regnumber"
                                    name="{{ $liabilities }}_account_or_regnumber"
                                    x-model="liabilitiesInfo.account_or_regnumber">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Cash in Bank & Deposit (£)
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $assets }}_cash_in_bank_and_deposit {{ $assets }}"
                                    name="{{ $assets }}_cash_in_bank_and_deposit"
                                    x-model="assetInfo.cash_in_bank_and_deposit">
                            </td>
                            <td colspan="2" class="td-label">
                                Personal Loans & Overdrafts
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_personal_loans_and_overdrafts {{ $errors->has($liabilities . '_personal_loans_and_overdrafts') ? 'is-invalid' : '' }} {{ $liabilities }}"
                                    name="{{ $liabilities }}_personal_loans_and_overdrafts"
                                    x-model="liabilitiesInfo.personal_loans_and_overdrafts">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Publicly Listed Shares
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $assets }}_public_listed_shares {{ $assets }}"
                                    name="{{ $assets }}_public_listed_shares" x-model="assetInfo.public_listed_shares">
                            </td>
                            <td colspan="2" class="td-label">
                                Mortgages
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $liabilities }}_mortgages {{ $liabilities }}"
                                    name="{{ $liabilities }}_mortgages" x-model="liabilitiesInfo.mortgages">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Properties Yes/No (See below)
                            </td>
                            <td colspan="2" class="td-value">
                                <select class="w-100 border-0 outline-none {{ $assets }}_properties"
                                    name="{{ $assets }}_properties" x-model="assetInfo.properties">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </td>
                            <td colspan="2" class="td-label">
                                Credit Card Debts
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $liabilities }}_credit_card_debts {{ $liabilities }}"
                                    name="{{ $liabilities }}_credit_card_debts"
                                    x-model="liabilitiesInfo.credit_card_debts">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Motor Vehicles & Boats
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $assets }}_motor_vehicles_boats {{ $assets }}"
                                    name="{{ $assets }}_motor_vehicles_boats" x-model="assetInfo.motor_vehicles_boats">
                            </td>
                            <td colspan="2" class="td-label">
                                Motor Loan
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $liabilities }}_motor_loan {{ $liabilities }}"
                                    name="{{ $liabilities }}_motor_loan" x-model="liabilitiesInfo.motor_loan">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Other Cash Investments
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $assets }}_other_cash_investments {{ $assets }}"
                                    name="{{ $assets }}_other_cash_investments"
                                    x-model="assetInfo.other_cash_investments">
                            </td>
                            <td colspan="2" class="td-label">
                                Property Rental
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $liabilities }}_property_rental {{ $liabilities }}"
                                    name="{{ $liabilities }}_property_rental" x-model="liabilitiesInfo.property_rental">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Details of Personal Pension
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $assets }}_details_of_personal_pension {{ $assets }}"
                                    name="{{ $assets }}_details_of_personal_pension"
                                    x-model="assetInfo.details_of_personal_pension">
                            </td>
                            <td colspan="2" class="td-label">
                                Other Debts & Contingent Liabilities
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_other_debt_and_contingent_liabilities {{ $liabilities }}"
                                    name="{{ $liabilities }}_other_debt_and_contingent_liabilities"
                                    x-model="liabilitiesInfo.other_debt_and_contingent_liabilities">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Any other assets (please specify)
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $assets }}_other_assets {{ $assets }}"
                                    name="{{ $assets }}_other_assets" x-model="assetInfo.other_assets">
                            </td>
                            <td colspan="2" class="td-label">
                                Any other liabilities (please specify) recurring or otherwise
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text" class="{{ $liabilities }}_other_liabilities {{ $liabilities }}"
                                    name="{{ $liabilities }}_other_liabilities"
                                    x-model="liabilitiesInfo.other_liabilities">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" class="td-label">Total (£)</th>
                            <td colspan="2" class="td-footer-value" x-text="$store.funding.formatCurrency(totalAssets)">
                            </td>
                            <th colspan="2" class="td-label">Total (£)</th>
                            <td colspan="2" class="td-footer-value"
                                x-text="$store.funding.formatCurrency(totalLiabilities)"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Properties and other assets -->
            <div x-data="dynamicRow({{ $otherAssets ?? '' }}, [{ property_address_and_assets: '', estimated_value: '', debt: '', financing_costs: '', income: '' }], 'y', {
                estimated_value: 0,
                debt: 0,
                financing_costs: 0,
                income: 0,
            })" class="properties-wrapper properties-other-assets overflow-auto">
                <div class="table-header d-flex justify-content-between align-items-center mb-1">
                    @php
                    $index = 'has_properties';
                    @endphp
                    <h5>Do you own any properties or other assets with a value greater than £5000?</h5>
                    <select name="{{ $index }}" x-model="selectInputValue"
                        class="propertySelect question border-0 outline-none form-select {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}">
                        <option value="y">Yes</option>
                        <option value="n">No</option>
                    </select>
                </div>

                <div x-show="selectInputValue === 'y'" :class="{'d-flex':selectInputValue === 'y'}"
                    class="income-header propertyTableHeader justify-content-between align-items-center" x-cloak>
                    <h2 class="header-text">Properties and other assets with value more than £5,000</h2>
                    <h6 class="header-text add-row" title="Add row" @click="addRow">
                        <x-icon-plus />
                    </h6>
                </div>

                <table x-show="selectInputValue === 'y'" class="propertyTable full-width table-collapse" x-cloak>
                    <thead>
                        <tr>
                            <th class="header-cell">Property Address(es) and Assets</th>
                            <th class="header-cell">Estimated Value (£)</th>
                            <th class="header-cell">Debt (if any) (£)</th>
                            <th class="header-cell">Financing Costs (£)</th>
                            <th class="header-cell">Income (if any) (£)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in rows" :key="row.key">
                            <tr>
                                <td class="table-cell">
                                    <input type="text" :name="`otherAssets[${index}][property_address_and_assets]`"
                                        x-model="row.property_address_and_assets">
                                </td>
                                <td class="table-cell">
                                    <input type="text" :name="`otherAssets[${index}][estimated_value]`"
                                        x-model="row.estimated_value" @input="updateTotals()"
                                        @focus="$el.value = $store.funding.stripCurrency($el.value)"
                                        @blur="$el.value = $store.funding.formatCurrency($el.value)">
                                </td>
                                <td class="table-cell">
                                    <input type="text" :name="`otherAssets[${index}][debt]`" x-model="row.debt"
                                        @input="updateTotals()"
                                        @focus="$el.value = $store.funding.stripCurrency($el.value)"
                                        @blur="$el.value = $store.funding.formatCurrency($el.value)">
                                </td>
                                <td class="table-cell">
                                    <input type="text" :name="`otherAssets[${index}][financing_costs]`"
                                        x-model="row.financing_costs" @input="updateTotals()"
                                        @focus="$el.value = $store.funding.stripCurrency($el.value)"
                                        @blur="$el.value = $store.funding.formatCurrency($el.value)">
                                </td>
                                <td class="table-cell position-relative">
                                    <input type="text" :name="`otherAssets[${index}][income]`" x-model="row.income"
                                        @input="updateTotals()"
                                        @focus="$el.value = $store.funding.stripCurrency($el.value)"
                                        @blur="$el.value = $store.funding.formatCurrency($el.value)">
                                    <span class="delete-row" title="Remove row" @click="removeRow(index)">
                                        <x-icon-minus />
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody>
                        <tr>
                            <td class="td-label">Total (£)</td>
                            <td class="td-footer-value" x-text="$store.funding.formatCurrency(totals.estimated_value)">
                            </td>
                            <td class="td-footer-value" x-text="$store.funding.formatCurrency(totals.debt)"></td>
                            <td class="td-footer-value" x-text="$store.funding.formatCurrency(totals.financing_costs)">
                            </td>
                            <td class="td-footer-value" x-text="$store.funding.formatCurrency(totals.income)"></td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <!-- Contingent Liabilities -->
            <div x-data="dynamicRow({{ $contingentLib ?? [] }}, [{ creditor: '', nature_of_pg: '', unlimited_guarantee_or_limit_value: ''}], 'y')"
                class="properties-wrapper household-income house-hold overflow-auto">
                <div class="table-header d-flex justify-content-between align-items-center mb-1">
                    @php
                    $index = 'has_contingentLib';
                    @endphp
                    <h5>Do you have any contingent liabilities (personal guarantees)?</h5>
                    <select name="{{ $index }}" x-model="selectInputValue"
                        class="propertySelect question border-0 outline-none form-select">
                        <option value="y">Yes</option>
                        <option value="n">No</option>
                    </select>
                </div>

                <div x-cloak x-show="selectInputValue === 'y'" :class="{'d-flex':selectInputValue === 'y'}"
                    class="income-header propertyTableHeader justify-content-between align-items-center">
                    <h2 class="header-text">Contingent Liabilities (Personal Guarantees)</h2>
                    <h6 class="header-text" @click="addRow">
                        <x-icon-plus />
                    </h6>
                </div>

                <table x-show="selectInputValue === 'y'" border="1" class="propertyTable table-collapse" x-cloak>
                    <thead>
                        <tr>
                            <th class="header-cell left-padding">Creditor</th>
                            <th class="header-cell left-padding">Nature of PG (supplier vs lender)</th>
                            <th class="header-cell left-padding">Unlimited Guarantee or Limit Value (£)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in rows" :key="row.key">
                            <tr>
                                <td class="table-cell">
                                    <input type="text" :name="`contingentLib[${index}][creditor]`"
                                        x-model="row.creditor">
                                </td>
                                <td class="table-cell">
                                    <input type="text" :name="`contingentLib[${index}][nature_of_pg]`"
                                        x-model="row.nature_of_pg">
                                </td>
                                <td class="table-cell position-relative">
                                    <input type="text" @focus="$el.value = $store.funding.stripCurrency($el.value)"
                                        @blur="$el.value = $store.funding.formatCurrency($el.value)"
                                        :name="`contingentLib[${index}][unlimited_guarantee_or_limit_value]`"
                                        x-model="row.unlimited_guarantee_or_limit_value">
                                    <span class="delete-row" title="Remove row" @click="removeRow(index)">
                                        <x-icon-minus />
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>


            <!-- Household Income -->
            <div x-data="dynamicRow({{ $householdIncome ?? [] }}, [{ type_and_source: '', who_in_household: '', gross_annual_income: ''}], 'y')"
                class="properties-wrapper household-income house-hold overflow-auto">
                <div class="table-header d-flex justify-content-between align-items-center mb-1">
                    @php
                    $index = 'has_householdIncome';
                    @endphp
                    <h5>Do you have any other household income?</h5>
                    <select name="{{ $index }}" x-model="selectInputValue"
                        class="propertySelect question border-0 outline-none form-select">
                        <option value="y">Yes</option>
                        <option value="n">No</option>
                    </select>
                </div>

                <div x-cloak x-show="selectInputValue === 'y'" :class="{'d-flex':selectInputValue === 'y'}"
                    class="income-header propertyTableHeader justify-content-between align-items-center">
                    <h2 class="header-text">Household Income</h2>
                    <h6 class="header-text add-row" @click="addRow">
                        <x-icon-plus />
                    </h6>
                </div>

                <table x-show="selectInputValue === 'y'" border="1" class="propertyTable table-collapse" x-cloak>
                    <thead>
                        <tr>
                            <th class="header-cell left-padding">Type and sources, e.g., dividends, salary/employment,
                                benefits, investments</th>
                            <th class="header-cell left-padding">Who in your household?</th>
                            <th class="header-cell left-padding">Gross Annual Income (£)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in rows" :key="row.key">
                            <tr>
                                <td class="table-cell">
                                    <input type="text" :name="`householdIncome[${index}][type_and_source]`"
                                        x-model="row.type_and_source">
                                </td>
                                <td class="table-cell">
                                    <input type="text" :name="`householdIncome[${index}][who_in_household]`"
                                        x-model="row.who_in_household">
                                </td>
                                <td class="table-cell position-relative">
                                    <input type="text" :name="`householdIncome[${index}][gross_annual_income]`"
                                        x-model="row.gross_annual_income"
                                        @focus="$el.value = $store.funding.stripCurrency($el.value)"
                                        @blur="$el.value = $store.funding.formatCurrency($el.value)">
                                    <span class="delete-row" title="Remove row" @click="removeRow(index)">
                                        <x-icon-minus />
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Unlisted Shares -->
            <div x-data="dynamicRow({{ $unlistedShares ?? [] }}, [{ company_name: '', reg_number: '', status: '0', registered: '', shareholding: ''}], 'y')"
                class="properties-wrapper unlisted-shares overflow-auto">

                <div class="table-header d-flex justify-content-between align-items-center mb-1">
                    @php
                    $index = 'has_unlistedShares';
                    @endphp
                    <h5>Do you have any unlisted shares?</h5>
                    <select name="{{ $index }}" x-model="selectInputValue"
                        class="propertySelect question border-0 outline-none form-select">
                        <option value="y">Yes</option>
                        <option value="n">No</option>
                    </select>
                </div>

                <div x-cloak x-show="selectInputValue === 'y'" :class="{'d-flex': selectInputValue === 'y'}"
                    class="income-header propertyTableHeader justify-content-between align-items-center">
                    <h2 class="header-text">Unlisted Shares</h2>
                    <h6 class="header-text add-row" @click="addRow">
                        <x-icon-plus />
                    </h6>
                </div>

                <table x-show="selectInputValue === 'y'" border="1" class="propertyTable table-collapse" x-cloak>
                    <thead>
                        <tr>
                            <th class="header-cell left-padding">Company Name</th>
                            <th class="header-cell left-padding">Registration Number</th>
                            <th class="header-cell left-padding">Status</th>
                            <th class="header-cell left-padding">Registered</th>
                            <th class="header-cell left-padding">% Shareholding</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in rows" :key="row.key">
                            <tr>
                                <td class="table-cell">
                                    <input type="text" :name="`unlistedShares[${index}][company_name]`"
                                        x-model="row.company_name">
                                </td>
                                <td class="table-cell">
                                    <input type="text" :name="`unlistedShares[${index}][reg_number]`"
                                        x-model="row.reg_number">
                                </td>
                                <td class="table-cell">
                                    <select class="w-100 border-0 outline-none"
                                        :name="`unlistedShares[${index}][status]`" x-model="row.status">
                                        <option value="0" :selected="row.status === '0'">Inactive</option>
                                        <option value="1" :selected="row.status === '1'">Active</option>
                                    </select>
                                </td>
                                <td class="table-cell">
                                    <input type="text" :name="`unlistedShares[${index}][registered]`"
                                        x-model="row.registered">
                                </td>
                                <td class="table-cell position-relative">
                                    <input type="text" :name="`unlistedShares[${index}][shareholding]`"
                                        x-model="row.shareholding">
                                    <span class="delete-row" title="Remove row" @click="removeRow(index)">
                                        <x-icon-minus />
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>


            <div class="">
                <div class="notes mb-2">
                    <p>*Please add any additional company’s not already included in the above table.</p>
                </div>

                <div class="notes">
                    <p>Data Protection Acknowledgment, Consent and Declaration </p>
                </div>

                <div class="desc">
                    <p class="text">By providing this information you agree and consent to Funding Alternative Group Ltd
                        and any
                        of its subsidiaries to (a) use and retain your personal information as reasonably required and
                        according
                        to our General Data Protection Regulation (GDPR) Policy <a
                            href="#">(www.fundingalternative.co.uk/privacy-policy)</a>,
                        (b) contact and enquire registered credit bureaux and other credit reporting agencies to
                        investigate
                        your creditworthiness including indebtedness, payment patterns, behaviour profile, (c) exchange
                        with
                        other credit providers or other third parties, your and your clients payment behaviour as well
                        as any
                        other information provided to us, and (d) contact, request and obtain information from other
                        credit
                        providers or third parties such as previous employers, trade and business partners and (e) act
                        as
                        personal Guarantor in relation to any possible lending arrangements made by us to your company.
                        You are
                        also consenting to continuing to receive electronic communications from our group of companies.
                        You may
                        unsubscribe or withdraw your consent at any time by sending an email to
                        <a href="#">applications@fundingalternative.co.uk.</a>
                    </p>
                    <p class="text">The personal information we have collected from you will be shared with fraud
                        prevention
                        agencies who will use it to prevent fraud and money-laundering and to verify your identity. If
                        fraud is
                        detected, you could be refused certain services, finance, or employment. Further details of how
                        your
                        information will be used by us and these fraud prevention agencies, and your data protection
                        rights, can
                        be found in our <a href="#">privacy policy</a>.</p>
                    <p class="bold-text">The proposed Guarantor confirms that (i) the above information is true and
                        correct to
                        the best of my knowledge, (ii) I have disclosed all the information pertaining to my
                        creditworthiness,
                        financial situation, assets and liabilities, and sources of income and (iii) have not hidden any
                        information that may reasonably influence my current and future financial situation and the loan
                        /
                        lending application to Funding Alternative Group Ltd or any of its subsidiaries. By signing this
                        statement, the proposed Guarantor is aware that the information supplied will be used by Funding
                        Alternative Group Ltd for the purposes of making a decision as to whether to provide a facility
                        to the
                        Company</p>
                </div>
            </div>

            <div class="footer-content ">
                <table class="footer-table" border="1">
                    <tr>
                        <td class="highlighted-cell">Guarantor signature</td>
                        <td class="position-relative bg-white" style="min-width: 100px;" x-data="signaturePadComponent"
                            x-init="'{{ $directorInfo->signature }}' !== '' ? (signatureData = '{{ asset('signatures/' . $directorInfo->signature) }}') : '';">

                            <button type="button" class="btn signature-btn" data-bs-toggle="modal"
                                data-bs-target="#signatureModel" id="signatureModelBtn"></button>
                            <div class="prev-image">
                                <img x-cloak x-show="signatureData" id="signPrev" :src="signatureData" alt="Signature"
                                    class="img-fluid" width="100px">
                                <span x-cloak x-show="!signatureData" id="signPrev" class="sign-prev text-center">Click
                                    here...</span>
                            </div>

                            <!-- Signature Modal -->
                            <div class="modal fade" id="signatureModel" tabindex="-1"
                                aria-labelledby="signatureModelLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="signatureModelLabel">Draw Your Signature</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="signature" x-model="signatureData">
                                            <canvas class="border" style="width: 100%; height: 300px;"
                                                x-ref="canvas"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="clearSignature" class="btn btn-secondary"
                                                @click="resetSignaturePad">Clear</button>
                                            <button type="button" id="saveSignature" class="btn btn-primary"
                                                @click="saveSignature" data-bs-dismiss="modal">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>


                        <td class="highlighted-cell">Print Full Name</td>
                        <td class="bg-white">
                            <span
                                x-text="`${directorInfo.first_name || ''} ${directorInfo.middle_name || ''} ${directorInfo.surname || ''}`.trim()"></span>
                        </td>

                        <td class="highlighted-cell">Date</td>
                        <td class="bg-white">
                            <input type="date" id="guarantor_date" name="guarantor_date" value="{{ date('Y-m-d') }}">
                        </td>
                    </tr>
                </table>
            </div>

            <div class=" mt-4">
                <button @click.prevent="saveData()" type="submit" class="btn btn-custom save-aip-data"
                    :disabled="loadingStates.saveData">
                    <span class="spinner-border spinner-border-sm processing-show"
                        :class="loadingStates.saveData ? '' : 'd-none'" role="status" aria-hidden="true"></span>
                    <span class="processing-show" :class="loadingStates.saveData ? '' : 'd-none'">Fetching...</span>
                    <span class="default-show" :class="loadingStates.saveData ? 'd-none' : ''">Save</span>
                </button>

                <button @click.prevent="exportButton()" type="button" class="btn btn-custom download-aip-doc"
                    :disabled="loadingStates.exportPDF">
                    <span class="spinner-border spinner-border-sm processing-show"
                        :class="loadingStates.exportPDF ? '' : 'd-none'" role="status" aria-hidden="true"></span>
                    <span class="processing-show" :class="loadingStates.exportPDF ? '' : 'd-none'">Loading...</span>
                    <span class="default-show" :class="loadingStates.exportPDF ? 'd-none' : ''">Export PDF</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection


@section('css-lib')
<link rel="stylesheet" href="{{ asset('css/sop.css') }}">
@endsection

@section('page-css')
<style>
    .content-page {
        margin: 0
    }
</style>
@endsection

@section('js-lib')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="{{ asset('js/x-main.js') }}"></script>
@endsection

@section('js')
<script>
    window.appData = {};
</script>
@endsection