@extends('guest')
@section('title', Helper::getSiteTitle('Statement of Position'))


@section('content')

<div class="container">
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


    <form id="sopForm" action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
        @csrf
        <div id="content" class="" style="position: relative;">
            {{-- <div class="d-flex justify-content-center align-items-center position-fixed"
                style="top:0;left:0;right:0;bottom:0;z-index: 999;">
                <img src="<?= get_stylesheet_directory_uri(); ?>/assets/image/spinner.gif" alt="" width="60">
            </div> --}}

            <div class="container mb-1">
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
                            value="{{ old($index . '_business_name', $businessInfo->business_name ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- Name and other details -->
            <div class="name-and-other-details container mb-1">
                <h2>Name and Other Details of Proposed Guarantor</h2>
                @php $index = 'proposed_guarantor_details'; @endphp
                <table class="table_{{ $index }}">
                    <tr>
                        <td class="td-label width-60">Title</td>
                        <td class="td-value">
                            <input type="text" name="{{ $index }}_title"
                                value="{{ old($index . '_title', $directorInfo->title ?? '') }}">
                        </td>
                        <td class="td-label width-110">First Name</td>
                        <td class="td-value">
                            <input type="text"
                                class="{{ $index }}_first_name {{ $errors->has($index . '_first_name') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_first_name"
                                value="{{ old($index . '_first_name', $directorInfo->first_name ?? '') }}">
                        </td>
                        <td class="td-label width-110">Middle Name</td>
                        <td class="td-value">
                            <input type="text"
                                class="{{ $index }}_middle_name {{ $errors->has($index . '_middle_name') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_middle_name"
                                value="{{ old($index . '_middle_name', $directorInfo->middle_name ?? '') }}">
                        </td>
                        <td class="td-label width-110">Last Name</td>
                        <td class="td-value">
                            <input type="text"
                                class="{{ $index }}_surname {{ $errors->has($index . '_surname') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_surname"
                                value="{{ old($index . '_surname', $directorInfo->surname ?? '') }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="td-label width-110">Full Street Address</td>
                        <td class="td-value" colspan="5">
                            <input type="text"
                                class="{{ $index }}_address_simple_value {{ $errors->has($index . '_address_simple_value') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_address_simple_value"
                                value="{{ old($index . '_address_simple_value', $directorInfo->address_simple_value ?? '') }}">
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
                            (Y/N)
                        </td>
                        <td class="td-value" colspan="2">
                            <select
                                class="w-100 border-0 outline-none {{ $index }}_declared_bankrupt {{ $errors->has($index . '_declared_bankrupt') ? 'is-invalid' : '' }}"
                                name="{{ $index }}_declared_bankrupt">
                                <option value="n" {{ old($index . '_declared_bankrupt' , $directorInfo->
                                    declared_bankrupt ?? '') == 'n' ? 'selected' : '' }}>N</option>
                                <option value="y" {{ old($index . '_declared_bankrupt' , $directorInfo->
                                    declared_bankrupt ?? '') == 'y' ? 'selected' : '' }}>Y</option>
                            </select>

                        </td>
                    </tr>
                </table>
            </div>

            <!-- Assets Liabilities -->
            <div class="assets-liabilities border-0 container">
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
            <div class="assets-details mb-1 container">
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
                                <input type="text"
                                    class="{{ $assets }}_account_or_regnumber {{ $errors->has($assets . '_account_or_regnumber') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_account_or_regnumber"
                                    value="{{ old($assets . '_account_or_regnumber', $assetInfo->account_or_regnumber ?? '') }}">
                            </td>
                            <th colspan="2" class="body-t-head">
                                Where appropriate please provide account/registration numbers
                            </th>
                            <td colspan="2" class="body-t-value">
                                <input type="text"
                                    class="{{ $liabilities }}_account_or_regnumber {{ $errors->has($liabilities . '_account_or_regnumber') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_account_or_regnumber"
                                    value="{{ old($liabilities . '_account_or_regnumber', $liabilitiesInfo->account_or_regnumber ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Cash in Bank & Deposit (£)
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $assets }}_cash_in_bank_and_deposit {{ $errors->has($assets . '_cash_in_bank_and_deposit') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_cash_in_bank_and_deposit"
                                    value="{{ old($assets . '_cash_in_bank_and_deposit', $assetInfo->cash_in_bank_and_deposit ?? '') }}">
                            </td>
                            <td colspan="2" class="td-label">
                                Personal Loans & Overdrafts
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_personal_loans_and_overdrafts {{ $errors->has($liabilities . '_personal_loans_and_overdrafts') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_personal_loans_and_overdrafts"
                                    value="{{ old($liabilities . '_personal_loans_and_overdrafts', $liabilitiesInfo->personal_loans_and_overdrafts ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Publicly Listed Shares
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $assets }}_public_listed_shares {{ $errors->has($assets . '_public_listed_shares') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_public_listed_shares"
                                    value="{{ old($assets . '_public_listed_shares', $assetInfo->public_listed_shares ?? '') }}">
                            </td>
                            <td colspan="2" class="td-label">
                                Mortgages
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_mortgages {{ $errors->has($liabilities . '_mortgages') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_mortgages"
                                    value="{{ old($liabilities . '_mortgages', $liabilitiesInfo->mortgages ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Properties Y/N (See below)
                            </td>
                            <td colspan="2" class="td-value">
                                <select
                                    class="w-100 border-0 outline-none {{ $assets }}_properties {{ $errors->has($assets . '_properties') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_properties">
                                    <option value="0" {{ old($assets . '_properties' , $assetInfo->
                                        properties ?? '') == 0 ? 'selected' : '' }}>N</option>
                                    <option value="1" {{ old($assets . '_properties' , $assetInfo->
                                        properties ?? '') == 1 ? 'selected' : '' }}>Y</option>
                                </select>
                            </td>
                            <td colspan="2" class="td-label">
                                Credit Card Debts
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_credit_card_debts {{ $errors->has($liabilities . '_credit_card_debts') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_credit_card_debts"
                                    value="{{ old($liabilities . '_credit_card_debts', $liabilitiesInfo->credit_card_debts ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Motor Vehicles & Boats
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $assets }}_motor_vehicles_boats {{ $errors->has($assets . '_motor_vehicles_boats') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_motor_vehicles_boats"
                                    value="{{ old($assets . '_motor_vehicles_boats', $assetInfo->motor_vehicles_boats ?? '') }}">
                            </td>
                            <td colspan="2" class="td-label">
                                Motor Loan
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_motor_loan {{ $errors->has($liabilities . '_motor_loan') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_motor_loan"
                                    value="{{ old($liabilities . '_motor_loan', $liabilitiesInfo->motor_loan ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Other Cash Investments
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $assets }}_other_cash_investments {{ $errors->has($assets . '_other_cash_investments') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_other_cash_investments"
                                    value="{{ old($assets . '_other_cash_investments', $assetInfo->other_cash_investments ?? '') }}">
                            </td>
                            <td colspan="2" class="td-label">
                                Property Rental
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_property_rental {{ $errors->has($liabilities . '_property_rental') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_property_rental"
                                    value="{{ old($liabilities . '_property_rental', $liabilitiesInfo->property_rental ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Details of Personal Pension
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $assets }}_details_of_personal_pension {{ $errors->has($assets . '_details_of_personal_pension') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_details_of_personal_pension"
                                    value="{{ old($assets . '_details_of_personal_pension', $assetInfo->details_of_personal_pension ?? '') }}">
                            </td>
                            <td colspan="2" class="td-label">
                                Other Debts & Contingent Liabilities
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_other_debt_and_contingent_liabilities {{ $errors->has($liabilities . '_other_debt_and_contingent_liabilities') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_other_debt_and_contingent_liabilities"
                                    value="{{ old($liabilities . '_other_debt_and_contingent_liabilities', $liabilitiesInfo->other_debt_and_contingent_liabilities ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-label">
                                Any other assets (please specify)
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $assets }}_other_assets {{ $errors->has($assets . '_other_assets') ? 'is-invalid' : '' }}"
                                    name="{{ $assets }}_other_assets"
                                    value="{{ old($assets . '_other_assets', $assetInfo->other_assets ?? '') }}">
                            </td>
                            <td colspan="2" class="td-label">
                                Any other liabilities (please specify) recurring or otherwise
                            </td>
                            <td colspan="2" class="td-value">
                                <input type="text"
                                    class="{{ $liabilities }}_other_liabilities {{ $errors->has($liabilities . '_other_liabilities') ? 'is-invalid' : '' }}"
                                    name="{{ $liabilities }}_other_liabilities"
                                    value="{{ old($liabilities . '_other_liabilities', $liabilitiesInfo->other_liabilities ?? '') }}">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" class="td-label">
                                Total (£)
                            </th>
                            <td colspan="2" class="td-value">
                                <input type="text">
                            </td>
                            <th colspan="2" class="td-label">
                                Total (£)
                            </th>
                            <td colspan="2" class="td-value">
                                <input type="text">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Properties and other assets -->
            <div class="properties-other-assets mb-1 container overflow-auto">
                <h2 class="table-header">
                    Properties and other assets with value more than £5,000
                </h2>
                <table class="full-width table-collapse">
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

                        <?php for ($i = 0; $i < 5; $i++) { ?>
                        <tr>
                            <td class="table-cell">
                                <input type="text" name="otherAssets[<?php echo $i; ?>][property_address_and_assets]"
                                    value="{{ old('otherAssets.' . $i . '.property_address_and_assets', $otherAssets[$i]->property_address_and_assets ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="otherAssets[<?php echo $i; ?>][estimated_value]"
                                    value="{{ old('otherAssets.' . $i . '.estimated_value', $otherAssets[$i]->estimated_value ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="otherAssets[<?php echo $i; ?>][debt]"
                                    value="{{ old('otherAssets.' . $i . '.debt', $otherAssets[$i]->debt ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="otherAssets[<?php echo $i; ?>][financing_costs]"
                                    value="{{ old('otherAssets.' . $i . '.financing_costs', $otherAssets[$i]->financing_costs ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="otherAssets[<?php echo $i; ?>][income]"
                                    value="{{ old('otherAssets.' . $i . '.income', $otherAssets[$i]->income ?? '') }}">
                            </td>
                        </tr>
                        <?php } ?>



                    </tbody>
                </table>
            </div>

            <!-- Contingent Liabilities -->
            <div class="household-income house-hold overflow-auto container mb-1">
                <h2 class="income-header">Contingent Liabilities (Personal Guarantees) </h2>
                <table border="1" class="table-collapse">
                    <thead>
                        <tr>
                            <th class="header-cell left-padding">Creditor</th>
                            <th class="header-cell left-padding">Nature of PG (supplier vs lender)</th>
                            <th class="header-cell left-padding">Unlimited Guarantee or Limit Value (£)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                        <tr>
                            <td class="table-cell">
                                <input type="text" name="contingentLib[<?php echo $i; ?>][creditor]"
                                    value="{{ old('contingentLib.' . $i . '.creditor', $contingentLib[$i]->creditor ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="contingentLib[<?php echo $i; ?>][nature_of_pg]"
                                    value="{{ old('contingentLib.' . $i . '.nature_of_pg', $contingentLib[$i]->nature_of_pg ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text"
                                    name="contingentLib[<?php echo $i; ?>][unlimited_guarantee_or_limit_value]"
                                    value="{{ old('contingentLib.' . $i . '.unlimited_guarantee_or_limit_value', $contingentLib[$i]->unlimited_guarantee_or_limit_value ?? '') }}">
                            </td>
                        </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Household Income -->
            <div class="household-income house-hold overflow-auto container mb-1">
                <h2 class="income-header">Household Income</h2>
                <table border="1" class="table-collapse">
                    <tbody>
                        <tr>
                            <th class="header-cell left-padding">Type and sources, e.g., dividends, salary/employment,
                                benefits,
                                investments</th>
                            <th class="header-cell left-padding">Who in your household?</th>
                            <th class="header-cell left-padding">Gross Annual Income (£)</th>
                        </tr>
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                        <tr>
                            <td class="table-cell">
                                <input type="text" name="householdIncome[<?php echo $i; ?>][type_and_source]"
                                    value="{{ old('householdIncome.' . $i . '.type_and_source', $householdIncome[$i]->type_and_source ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="householdIncome[<?php echo $i; ?>][who_in_household]"
                                    value="{{ old('householdIncome.' . $i . '.who_in_household', $householdIncome[$i]->who_in_household ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="householdIncome[<?php echo $i; ?>][gross_annual_income]"
                                    value="{{ old('householdIncome.' . $i . '.gross_annual_income', $householdIncome[$i]->gross_annual_income ?? '') }}">
                            </td>
                        </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Unlisted Shares -->
            <div class="unlisted-shares container mb-1 overflow-auto">
                <h2 class="income-header">Unlisted Shares</h2>
                <table border="1" class="table-collapse">
                    <tbody>
                        <tr>
                            <th class="header-cell left-padding">Company Name</th>
                            <th class="header-cell left-padding">Registration Number</th>
                            <th class="header-cell left-padding">Status</th>
                            <th class="header-cell left-padding">Registered</th>
                            <th class="header-cell left-padding">% Shareholding</th>
                        </tr>

                        <?php for ($i = 0; $i < 3; $i++) { ?>
                        <tr>
                            <td class="table-cell">
                                <input type="text" name="unlistedShares[<?php echo $i; ?>][company_name]"
                                    value="{{ old('unlistedShares.' . $i . '.company_name', $unlistedShares[$i]->company_name ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="unlistedShares[<?php echo $i; ?>][reg_number]"
                                    value="{{ old('unlistedShares.' . $i . '.reg_number', $unlistedShares[$i]->reg_number ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <select class="w-100 border-0 outline-none" name="unlistedShares[{{ $i }}][status]">
                                    <option value="0" {{ old('unlistedShares.' . $i . '.status' , $unlistedShares[$i]->
                                        status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                                    <option value="1" {{ old('unlistedShares.' . $i . '.status' , $unlistedShares[$i]->
                                        status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                </select>
                            </td>
                            <td class="table-cell">
                                <input type="text" name="unlistedShares[<?php echo $i; ?>][registered]"
                                    value="{{ old('unlistedShares.' . $i . '.registered', $unlistedShares[$i]->registered ?? '') }}">
                            </td>
                            <td class="table-cell">
                                <input type="text" name="unlistedShares[<?php echo $i; ?>][shareholding]"
                                    value="{{ old('unlistedShares.' . $i . '.shareholding', $unlistedShares[$i]->shareholding ?? '') }}">
                            </td>
                        </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="container">
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

            <div class="footer-content container">
                <table class="footer-table" border="1">
                    <tr>
                        <td class="highlighted-cell">Guarantor signature</td>
                        <td class="position-relative bg-white" style="min-width: 100px;">
                            <button type="button" class="btn signature-btn" data-bs-toggle="modal"
                                data-bs-target="#signatureModel" id="signatureModelBtn">
                            </button>
                            <div class="prev-image">
                                <div class="prev-image">
                                    @if (!empty($directorInfo->signature))
                                    <img id="signPrev" src="{{ asset('signatures/' . $directorInfo->signature) }}"
                                        alt="Signature" class="img-fluid" width="100px">
                                    @else
                                    <img id="signPrev" src="{{ asset('images/white.jpg') }}" alt="" class="img-fluid"
                                        width="100px">
                                    @endif
                                </div>

                            </div>

                        </td>

                        <td class="highlighted-cell">Print Full Name</td>
                        <td class="bg-white">
                            <input type="text" id="guarantor_name" name="guarantor_name"
                                value="{{ $directorInfo->name }}">
                        </td>

                        <td class="highlighted-cell">Date</td>
                        <td class="bg-white">
                            <input type="date" id="guarantor_date" name="guarantor_date">
                        </td>
                    </tr>
                </table>
            </div>

            <div class="container mt-4">
                <button id="submitSOPData" type="submit" class="btn btn-primary px-3 py-2">Submit</button>
            </div>

            <!-- Signature Modal -->
            <div class="modal fade" id="signatureModel" tabindex="-1" aria-labelledby="signatureModelLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="signatureModelLabel">Draw Your Signature</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="signature" id="signatureInput">
                            <!-- Canvas for Signature -->
                            <canvas id="signatureCanvas" class="border" style="width: 100%; height: 300px;"></canvas>
                        </div>
                        <div class="modal-footer">
                            <!-- Clear and Save buttons -->
                            <button type="button" id="clearSignature" class="btn btn-secondary">Clear</button>
                            <button type="button" id="saveSignature" class="btn btn-primary"
                                data-bs-dismiss="modal">Save changes</button>
                        </div>
                    </div>
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
@endsection

@section('js')
<script>
    let signaturePad;
    let canvas = $('#signatureCanvas')[0];

    function resizeCanvas() {
        let ratio = Math.max(window.devicePixelRatio || 1, 1);
        let width = $(canvas).outerWidth();
        let height = $(canvas).outerHeight(); 
        canvas.width = width * ratio;
        canvas.height = height * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    // Initialize SignaturePad when the modal is shown
    $('#signatureModel').on('shown.bs.modal', function () {
        resizeCanvas();
        signaturePad = new SignaturePad(canvas);
    });

    // Save signature as image when clicking Save
    $('#saveSignature').on('click', function () {
        if (signaturePad.isEmpty()) {
            alert('Please provide a signature first.');
        } else {
            let dataUrl = signaturePad.toDataURL(); 
            $('#signatureInput').val(dataUrl);
            $('#signPrev').attr('src', dataUrl); 
            $('.prev-image').removeClass('d-none');
        }
    });

    // Clear the signature when clicking Clear
    $('#clearSignature').on('click', function () {
        signaturePad.clear();
        $('#signPrev').attr('src', '');
        $('.prev-image').addClass('d-none');
    });

    // Ensure the canvas resizes correctly on window resize
    $(window).on('resize', resizeCanvas); 

    // prompt to download PDF
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('sopForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Download PDF?',
                text: "Do you want to download a PDF of the submitted data?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, download it!',
                cancelButtonText: 'No, just submit',
            }).then((result) => {
                if (result.isConfirmed) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'download_pdf';
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