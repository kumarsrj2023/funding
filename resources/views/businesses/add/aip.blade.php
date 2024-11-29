@extends('index')
@section('title', Helper::getSiteTitle('AIP'))

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">AIP</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'AIP' }}</h4>
        </div>
    </div>
</div>
<div class="form-wrapper aip" id="aip" x-data="aip({
    saveAIPData: '{{ route('businesses.aip', $id) }}',
    currentUrl: '{{ URL::current() }}',
    aipDoc: '{{ route('download.aip', $id) }}'
})" )">
    <form id="aipForm" action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            @include('includes.show-message', ['extra_class' => 'col-12 mb-2'])
            <div x-cloak x-show="errors.isError" id="displayMessages" class="display-messages col-12 mb-2">
                <div class="alert alert-danger" role="alert">
                    <template x-for="(error, index) in errors.errorList" :key="index">
                        <p class="mb-1" x-text="error"></p>
                    </template>
                </div>
            </div>
            <div x-cloak x-show="message" class="alert alert-success" role="alert" x-text="message"></div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(!empty($id))
                        @include('businesses.add.nav', ['id' => $id])
                        @endif
                        <div class="row" id="content">
                            <div class="col-12">
                                <h2 class="title mb-1 text-center">Congratulations, you’ve been pre-approved!</h2>
                                <p class="description">
                                    Here are our Headline Terms of Offer, which are subject to satisfactory due
                                    diligence checks, provision of Further Information and the conclusion of any
                                    Conditions Precedent (as outlined below).
                                </p>
                                <p>
                                    <strong>
                                        When these are in place, we can sign the funding agreements, and fund upon
                                        signing of the agreement!
                                    </strong>
                                </p>

                                @php
                                $table_name = 'terms_of_advance';
                                @endphp
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered {{ $table_name }}">
                                            <thead class="thead-dark">
                                                <tr class="table-main-header">
                                                    <th colspan="100%" class="text-start">Terms of Advance</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="td-label">Advance</td>
                                                    <td class="td-value"> {{ !empty($price_model->advance_requested) ?
                                                        ('£' . $price_model->advance_requested) : '' }} </td>
                                                    <td class="td-label">Servicing and Administration Fee </td>
                                                    <td class="td-value"> {{
                                                        !empty($price_model->arrangement_fee_excl_VAT) ? ('£' .
                                                        $price_model->arrangement_fee_excl_VAT . ' + VAT') : '' }} </td>
                                                </tr>
                                                <tr>
                                                    <td class="td-label">Maximum Total Repayment</td>
                                                    <td class="td-value"> {{ !empty($price_model->total_repayable) ?
                                                        ('£' . $price_model->total_repayable) : '' }} </td>
                                                    <td class="td-label">Purpose of Advance</td>
                                                    <td class="td-value"> {{
                                                        !empty($business_detail->reason_for_funding) ?
                                                        $business_detail->reason_for_funding : ''}} </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @php
                                $table_name = 'weekly_repayment_option';
                                @endphp
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered {{ $table_name }}">
                                            <thead class="thead-dark">
                                                <tr class="table-main-header">
                                                    <th colspan="100%" class="text-start">Weekly Repayment Option –
                                                        Fixed Weekly Repayment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="td-label">Maturity</td>
                                                    <td class="td-value">6 months</td>
                                                    <td class="td-label">Weekly Repayment </td>
                                                    <td class="td-value"> {{
                                                        !empty($price_model->fixed_repayment_amount) ? ('£' .
                                                        $price_model->fixed_repayment_amount) : ''}} </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @php
                                $table_name = 'daily_Repayment_option';
                                @endphp
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered {{ $table_name }}">
                                            <thead class="thead-dark">
                                                <tr class="table-main-header">
                                                    <th colspan="100%" class="text-start">Daily Repayment Option – Fixed
                                                        Daily Repayment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="td-label">Maturity</td>
                                                    <td class="td-value">6 months</td>
                                                    <td class="td-label">Daily Repayment </td>
                                                    <td class="td-value">{{ !empty($price_model->daily_repayment_amount)
                                                        ? ('£' . $price_model->daily_repayment_amount) : '' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @php
                                $table_name = 'your_company_information';
                                @endphp
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered {{ $table_name }}">
                                            <thead class="thead-dark">
                                                <tr class="table-main-header">
                                                    <th colspan="100%" class="text-start">Your Company Information
                                                        (“You”)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="td-label" colspan="1">Business Name</td>
                                                    <td class="td-value" colspan="3"> {{
                                                        !empty($business_detail->business_name) ?
                                                        $business_detail->business_name : ''}} </td>
                                                </tr>
                                                <tr>
                                                    <td class="td-label" colspan="1">Trading Name</td>
                                                    <td class="td-value" colspan="3"> {{
                                                        !empty($business_detail->business_name) ?
                                                        $business_detail->business_name : ''}} </td>
                                                </tr>
                                                <tr>
                                                    <td class="td-label" colspan="1">Registered Address</td>
                                                    <td class="td-value" colspan="3"> {{
                                                        !empty($business_detail->address) ? $business_detail->address :
                                                        ''}} </td>
                                                </tr>
                                                <tr>
                                                    <td class="td-label" colspan="1">Type of Entity</td>
                                                    <td class="td-value" colspan="3">
                                                        <select
                                                            class="w-100 border-0 outline-none ? 'is-invalid' : '' }}"
                                                            name="type_of_entity" x-model="newData.type_of_entity">
                                                            <option value="">-- Select --</option>
                                                            <option value="limited company">Limited Company</option>
                                                            <option value="limited liability partnership">Limited
                                                                Liability Partnership</option>
                                                            <option value="solo proprietorship">Solo Proprietorship
                                                            </option>
                                                            <option value="partnership">Partnership</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="td-label" colspan="1">Registration No.</td>
                                                    <td class="td-value" colspan="3"> {{
                                                        !empty($business_detail->registration_number) ?
                                                        $business_detail->registration_number : ''}} </td>
                                                </tr>
                                                <tr>
                                                    <td class="td-label">Website</td>
                                                    <td class="td-value"> {{ !empty($business_detail->website_address) ?
                                                        $business_detail->website_address : ''}} </td>
                                                    <td class="td-label">Main Phone</td>
                                                    <td class="td-value"> {{ !empty($business_detail->switchboard_number) ?
                                                        $business_detail->switchboard_number : ''}} </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @php
                                $table_name = 'your_contact_details';
                                @endphp
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered {{ $table_name }}">
                                            <thead class="thead-dark">
                                                <tr class="table-main-header">
                                                    <th colspan="100%" class="text-start">Your Contact Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="td-label">Full Name</td>
                                                    <td class="td-value"> {{ trim($customerInfo->first_name . ' ' .
                                                        $customerInfo->middle_name . ' ' . $customerInfo->last_name) }}
                                                    </td>
                                                    <td class="td-label">Position </td>
                                                    <td class="td-value">-</td>
                                                </tr>
                                                <tr>
                                                    <td class="td-label">Email</td>
                                                    <td class="td-value"> {{ !empty($customerInfo->email) ?
                                                        $customerInfo->email : '' }}</td>
                                                    <td class="td-label">Mobile</td>
                                                    <td class="td-value"> {{ !empty($customerInfo->phone) ?
                                                        $customerInfo->phone : '' }} </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="content-wrapper">
                                    <h5 class="content-title">You’ve been pre-approved for a Business Cash Advance</h5>
                                    <p class="content-description">
                                        You’ve been pre-approved for a Business Cash Advance from the Funding
                                        Alternative Group! To finalise your Business Cash Advance, we need your signed
                                        agreement to a few items which we’ve listed under the ‘Accepting this Offer’
                                        section below. This preliminary offer is subject to terms as outlined in
                                        <a
                                            href="https://fundingalternative.co.uk/preliminary-offer-terms-bca/">https://fundingalternative.co.uk/preliminary-offer-terms-bca/</a>
                                        - by signing this
                                        agreement, you agree to these terms and conditions – please review these
                                        carefully – if you have any questions, please contact us at
                                        <a
                                            href="mailto: applications@fundingalternative.co.uk">applications@fundingalternative.co.uk</a>
                                        or call <a href="tel: 0800 652 1977">0800 652 1977</a>.
                                    </p>
                                </div>

                                <div class="content-wrapper">
                                    <h5 class="content-title">Assumptions We’ve Made in Determining this Offer</h5>
                                    <p class="content-description">
                                    <ul>
                                        <li>All materials are provided in good faith, are accurate and up to date,
                                            and have been completed to the best of your knowledge.</li>
                                        <li>The business is currently up to date with all taxes.</li>
                                        <li>Latest financial information is available upon request.</li>
                                        @php
                                        $ownership = $customerInfo->ownership ?? '';
                                        $isHomeowner = !empty($ownership) && (
                                        Helper::hasText('home owner', $ownership) ||
                                        Helper::hasText('Home Owner (Own Home)', $ownership)
                                        );
                                        @endphp

                                        <li>Directors {{ $isHomeowner ? 'are' : 'are not' }} homeowners with sufficient
                                            home equity.</li>
                                        <li>The business provides {{ !empty($business_detail->industry) ?
                                            str_replace('&nbsp;', '', $business_detail->industry) : '___' }}.</li>
                                        @if (Helper::hasText('construction and building', $business_detail->industry))
                                        <li>Signed contracts for both current
                                            and forthcoming projects are in place, substantiating revenue
                                            projections for the upcoming six months.</li>
                                        @endif



                                        <li>The cash flow projections for the business demonstrate a definitive
                                            capacity to service the required repayments associated with the
                                            requested advance.</li>
                                        <li>The directors' personal credit report obtained via TransUnion will not
                                            display any adverse information.</li>
                                        <li>{{ $business_detail->business_name }} will repay the Business Cash Advance
                                            through a Funding
                                            Alternative approved recurring payment provider.</li>
                                        <li>Open Banking will be provided prior and after funding.</li>
                                    </ul>
                                    </p>
                                </div>

                                <div class="content-wrapper">
                                    <h5 class="content-title">Conditions Precedent</h5>
                                    <p class="content-description">
                                        The borrower shall provide debenture on the company and its subsidiaries (if
                                        any) that will be filed with Companies House on or prior to the Funding Date.
                                        All directors and major shareholders shall provide a Personal Guarantee as well
                                        as a Statement of Financial Position, on the fulfilment of the terms, on or
                                        prior to the Funding Date.
                                    </p>
                                    <p class="content-description">
                                        The company shall authorise us to monitor the company’s bank accounts via Open
                                        Banking until such a time as the Principal Amount is repaid in full.
                                    </p>
                                </div>

                                <div class="content-wrapper">
                                    <h5 class="content-title">Data Consent</h5>
                                    <p class="content-description">
                                        By providing us with your personal information you consent to Funding
                                        Alternative Ltd using and retaining your personal information as is required and
                                        according to our General Data Protection Regulation (GDPR) Policy
                                        (https://fundingalternative.co.uk/privacy-policy/). You hereby give permission
                                        for us to process your personal data in a manner which is consistent with, and
                                        reasonably required for the effective performance of any potential agreement
                                        between us. You are also consenting to continuing to receive electronic
                                        communications from our group of companies.
                                    </p>
                                    <p class="content-description">
                                        Please note that you can withdraw your consent at any time by writing to the
                                        Information Officer at privacy@fundingalternative.co.uk.
                                    </p>
                                </div>

                                <div class="content-wrapper">
                                    <h5 class="content-title">Credit Searches and Identity Checks</h5>
                                    <p class="content-description">
                                        You hereby consent that, and authorise Funding Alternative Ltd to, from time to
                                        time and from the date of signature below until the termination of our
                                        agreement:
                                    </p>
                                    <p class="content-description">
                                    <ul class="dots-type-list">
                                        <li>
                                            ­contact and enquire registered credit bureaux and other credit reporting
                                            agencies to investigate your creditworthiness including indebtedness,
                                            payment patterns, behaviour profile
                                        </li>
                                        <li>
                                            ­ exchange with other credit providers or other third parties, you and your
                                            client’s payment behaviour as well as any other information provided to us,
                                            and
                                        </li>
                                        <li>
                                            ­ contact, request and obtain information from other credit providers or
                                            third parties such as previous employers, trade and business partners
                                        </li>
                                    </ul>
                                    </p>
                                    <p class="content-description">
                                        The personal information we have collected from you will be shared with fraud
                                        prevention agencies who will use it to prevent fraud and money-laundering and to
                                        verify your identity. If fraud is detected, you could be refused certain
                                        services, finance, or employment. Further details of how your information will
                                        be used by us and these fraud prevention agencies, and your data protection
                                        rights, can be found in our <a
                                            href="https://fundingaltgroup.co.uk/privacy-policy/">privacy policy.</a>
                                    </p>
                                </div>

                                <div class="content-wrapper">
                                    <h5 class="content-title">Further Information</h5>
                                    <p class="content-description">
                                        This offer is presented based on the assumptions we’ve made, and is subject to
                                        our complete satisfaction following our review of the additional materials
                                        below, including but not limited to:
                                    </p>
                                    <p class="content-description">
                                    <ul>
                                        @if (isset($price_model) && $price_model->advance_requested >= 50000)
                                        <li>Most recent Aged Debtors and Creditors List, including any tax arrears.</li>
                                        @endif

                                        <li>
                                            Provide a breakdown of the companies tax situation, stating the outstanding
                                            balances for VAT, PAYE and Corporate Tax. If applicable, please include any
                                            payment arrangements that are in place with supporting HMRC screenshots
                                            where applicable.
                                        </li>
                                        {{-- <li>
                                            REMOVE IF NO LENDERS Complete our lender table in full, outlining any
                                            existing debt alongside the payment details and security given (table
                                            included in accompanying email).
                                        </li>
                                        <li>
                                            [Provide signed contracts for current and future work that substantiate
                                            revenue over the next 6-months.] (If not needed, delete)
                                        </li> --}}
                                    </ul>
                                    </p>
                                </div>

                                <div class="properties-wrapper unlisted-shares container overflow-auto">
                                    <div
                                        class="d-none table-header d-flex justify-content-between align-items-center mb-1">
                                        @php
                                        $index = 'has_lender';
                                        @endphp
                                        <h5 class="text-center">Lender Table</h5>
                                        <select
                                            class="propertySelect question border-0 outline-none form-select {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}"
                                            name="{{ $index }}">
                                            <option value="n" {{ old($index)==='n' ? 'selected' : '' }}>No</option>
                                            <option value="y" {{ old($index, 'y' )==='y' ? 'selected' : '' }}>Yes
                                            </option>
                                        </select>
                                    </div>
                                    <div
                                        class="income-header propertyTableHeader justify-content-between align-items-center">
                                        <h2 class="header-text text-center m-auto">Lender Table</h2>
                                        <h6 class="header-text add-row">
                                            <x-icon-plus />
                                        </h6>
                                    </div>
                                    <table border="1" class="propertyTable table-collapse">
                                        <thead>
                                            <tr>
                                                <th class="header-cell left-padding">Lender Name</th>
                                                <th class="header-cell left-padding">Outstanding amount</th>
                                                <th class="header-cell left-padding">How much gets paid?</th>
                                                <th class="header-cell left-padding">How often it is paid?</th>
                                                <th class="header-cell left-padding">Expected Maturity</th>
                                                <th class="header-cell left-padding">Security given (PG, Asset? Etc.)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $allAssets = old('lender', $lender->toArray());
                                            $countLenders = count($allAssets) > 0 ? count($allAssets) : 1;
                                            @endphp
                                            <?php for ($i = 0; $i < $countLenders; $i++) { ?>
                                            <tr class="">
                                                <td class="table-cell">
                                                    <input type="text" name="lender[<?php echo $i; ?>][lender_name]"
                                                        value="{{ old('lender.' . $i . '.lender_name', $lender[$i]->lender_name ?? '') }}">
                                                </td>

                                                <td class="table-cell">
                                                    <input type="text"
                                                        name="lender[<?php echo $i; ?>][outstanding_amount]"
                                                        value="£{{ old('lender.' . $i . '.outstanding_amount', $lender[$i]->outstanding_amount ?? '') }}"
                                                        x-on:focus="if ($el.value.includes('£')) $el.value = $el.value.replace('£', '')"
                                                        x-on:blur="if (!$el.value.includes('£') && $el.value.trim() !== '') $el.value = '£' + $el.value">
                                                </td>

                                                <td class="table-cell">
                                                    <input type="text"
                                                        name="lender[<?php echo $i; ?>][how_much_gets_paid]"
                                                        value="£{{ old('lender.' . $i . '.how_much_gets_paid', $lender[$i]->how_much_gets_paid ?? '') }}"
                                                        x-on:focus="if ($el.value.includes('£')) $el.value = $el.value.replace('£', '')"
                                                        x-on:blur="if (!$el.value.includes('£') && $el.value.trim() !== '') $el.value = '£' + $el.value">
                                                </td>

                                                <td class="table-cell">
                                                    <input type="text"
                                                        name="lender[<?php echo $i; ?>][how_often_it_is_paid]"
                                                        value="{{ old('lender.' . $i . '.how_often_it_is_paid', $lender[$i]->how_often_it_is_paid ?? '') }}">
                                                </td>
                                                <td class="table-cell">
                                                    <input type="text"
                                                        name="lender[<?php echo $i; ?>][expected_maturity]"
                                                        value="{{ old('lender.' . $i . '.expected_maturity', $lender[$i]->expected_maturity ?? '') }}">
                                                </td>
                                                <td class="table-cell position-relative">
                                                    <input type="text" name="lender[<?php echo $i; ?>][security_given]"
                                                        value="{{ old('lender.' . $i . '.security_given', $lender[$i]->security_given ?? '') }}">

                                                    <span class="delete-row" title="Remove row">
                                                        <x-icon-minus />
                                                    </span>
                                                </td>
                                            </tr>

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="content-wrapper">
                                    <h5 class="content-title">Acceptance</h5>
                                    <p class="content-description">
                                        This Pre-Approval Offer is valid until close of business on {{ date('l jS F Y')
                                        }}.
                                    </p>
                                    <p class="content-description">
                                        By agreeing to the terms presented and signing below (which includes all
                                        accepted forms of electronic signature), you consent to proceed under these
                                        conditions and authorise the processing of your personal data.
                                    </p>

                                </div>

                                <div class="content-wrapper">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <p>Signed by </p>
                                                <p class="fw-bold">
                                                    <input type="text" class="date-input" class="signed-by fw-bold"
                                                        value="Warren Collocott" style="font-weight: 600">
                                                </p>
                                            </div>
                                            <div>
                                                <p>for and on behalf of </p>
                                                <p><strong>Funding Alternative Group Ltd</strong></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-wrapper authorised-wrapper">
                                                <div class="signature-wrapper position-relative">
                                                    <span x-cloak
                                                        @click="signatureUrl = ''; $refs.fileInput.value = '' "
                                                        x-show="signatureUrl"
                                                        class="position-absolute top-0 start-100 translate-middle badge border border-light rounded-circle bg-danger p-1">
                                                        <x-icon-x-mark />
                                                    </span>

                                                    <label
                                                        class="position-absolute top-0 start-0 bottom-0 end-0 d-flex justify-content-center align-items-center"
                                                        role="button">
                                                        <span x-show="!signatureUrl">Click here to upload a file</span>
                                                        <input type="file" x-ref="fileInput" @change="handleFileUpload"
                                                            style="display: none;">
                                                    </label>

                                                    <img x-cloak x-show="signatureUrl" :src="signatureUrl"
                                                        alt="Image Preview" class="signature-preview img-fluid"
                                                        style="max-height: 250px">
                                                </div>

                                                <p class="my-1">Authorised signatory</p>
                                                <p class="signature-date d-flex align-items-center">
                                                    <span class="my-1 me-1">Date: </span>
                                                    <input type="date" class="date-input">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button @click.prevent="saveData()" type="button" class="btn btn-custom save-aip-data">
                <span class="spinner-border spinner-border-sm processing-show"
                    :class="loadingStates.saveData ? '' : 'd-none'" role="status" aria-hidden="true"></span>
                <span class="processing-show" :class="loadingStates.saveData ? '' : 'd-none'">Fetching...</span>
                <span class="default-show" :class="loadingStates.saveData ? 'd-none' : ''">Save</span>
            </button>

            <button @click.prevent="exportButton()" type="button" class="btn btn-custom download-aip-doc">
                <span class="spinner-border spinner-border-sm processing-show"
                    :class="loadingStates.exportDoc ? '' : 'd-none'" role="status" aria-hidden="true"></span>
                <span class="processing-show" :class="loadingStates.exportDoc ? '' : 'd-none'">Loading...</span>
                <span class="default-show" :class="loadingStates.exportDoc ? 'd-none' : ''">Export Word Doc</span>
            </button>
        </div>

</div>
</form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectElements = document.querySelectorAll(".propertySelect");
        // Add Event listener to show or hide table
        selectElements.forEach(select => {
            select.addEventListener("change", function() {
                toggleTableVisibility(select);
            });
            toggleTableVisibility(select);
        });

        // Add event listener to all "Add row" buttons
        const addRowButtons = document.querySelectorAll('.add-row');
        addRowButtons.forEach(button => {
            button.addEventListener('click', function() {
                addRow(button);
            });
        });

        // Add event listener to all delete buttons on page load
        const deleteButtons = document.querySelectorAll('.delete-row');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                deleteRow(button);
            });
        });
    });

    function toggleTableVisibility(select) {
        const container = select.closest(".properties-wrapper");
        const table = container.querySelector(".propertyTable");
        const header = container.querySelector(".propertyTableHeader");

        if (select.value === 'y') {
            table.style.display = "table";
            header.style.display = "flex";
        } else {
            table.style.display = "none";
            header.style.display = "none";
        }
    }

    function addRow(button) {
        const tableBody = button.closest('.properties-wrapper').querySelector('tbody');
        const lastRow = tableBody.querySelector("tr:last-child");
        const newRow = lastRow.cloneNode(true);

        const currentIndex = tableBody.querySelectorAll("tr").length;

        newRow.querySelectorAll("input, select").forEach((input) => {
            const name = input.getAttribute("name");
            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${currentIndex}]`);
                input.setAttribute("name", newName);
                
                if (input.tagName.toLowerCase() === 'input') {
                    input.value = '';
                }
            }
        });

        tableBody.appendChild(newRow);

        newRow.querySelector('.delete-row').addEventListener('click', function() {
            deleteRow(this);
        });
    }

    function deleteRow(deleteButton) {
        const row = deleteButton.closest("tr");
        const tableBody = row.closest("tbody");

        if (tableBody.querySelectorAll("tr").length > 1) {
            row.remove();

            tableBody.querySelectorAll("tr").forEach((tr, index) => {
                tr.querySelectorAll("input, select").forEach(input => {
                    const name = input.getAttribute("name");
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute("name", newName);
                    }
                });
            });
        }
    }

    function aip(routes) {
        return {
            loadingStates: {
                saveData: false,
                exportDoc: false,
            },
            errors: {
                isError: false,
                errorList: {}
            },
            message: '',
            isLoading: false,
            newData: {},
            signatureUrl: '',
            
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.signatureUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            async saveData() {
                const self = this;

                const form = document.querySelector('form');
                const formData = new FormData(form);

                self.loadingStates.saveData = true; 

                try {
                    const response = await fetch(routes.currentUrl, {
                        method: 'POST',
                        body: formData,
                    });

                    const data = await response.json();

                    if (data.success) {
                        const routeUrl = routes.aipDoc;
                        
                        self.prompt(
                            'Success!',
                            'Would you like to download the submitted data as a Word document?',
                            routeUrl
                        );

                        self.errors.isError = false;
                        self.errors.errorList = {};
                        self.message = '';
                    } else {
                        self.errors.isError = true;

                        if (data.errors) {
                            self.errors.errorList = data.errors;
                        } else {
                            self.errors.errorList = { general: 'An error occurred while processing your request.' };
                        }

                        self.scrollSmooth();
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    self.errors.isError = true;
                    self.errors.errorList = { general: 'An unexpected error occurred. Please try again later.' };

                    self.scrollSmooth();
                } finally {
                    self.loadingStates.saveData = false; 
                }
            },

            prompt(title, text, url) {
                const self = this;

                Swal.fire({
                    title: title || 'Data Saved Successfully?',
                    text: text || "Do you want to download File?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, download it!',
                    cancelButtonText: 'No, Thanks!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        self.exportButton();
                    } else {
                        // console.log();
                    }
                });
            },

            exportButton() {
                const self = this;
                self.loadingStates.exportDoc = true;

                const form = document.querySelector("#aipForm");
                form.action = routes.aipDoc;
                form.submit();

                form.action = routes.currentUrl;

                setTimeout(() => {
                    self.loadingStates.exportDoc = false;
                }, 2000);
            },

            scrollSmooth() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth' 
                });
            },

            init() {
            
            }
        }
    }
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
<script src="//unpkg.com/alpinejs" defer></script>
@endsection