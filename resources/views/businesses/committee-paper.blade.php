@extends('index')
@section('title', Helper::getSiteTitle('Committee Paper'))
@section('page-css')
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Committee Paper</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'Committee Paper' }}</h4>
        </div>
    </div>
</div>
<div class="form-wrapper committee-paper" id="committeePaper" x-data="committeePaper({
    loanData: '{{ route('businesses.loanData', $id) }}',
    saveCommitteePaperData: '{{ route('businesses.saveCommitteePaperData', $id) }}',
    currentUrl: '{{ URL::current() }}',
    commiteePaperDoc: '{{ route('businesses.commiteePaperDoc', [$id, ':loanId']) }}'
})" )">
    <form action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
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
                                <h5 class="table-main-header mb-0 text-center">SECTION ONE: Company and
                                    Application Details</h5>
                                <div class="table-responsive" style="overflow-x: auto">
                                    <table class="table first-table table-bordered"
                                        style="table-layout: fixed; width: 100%;"
                                        >
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="table-section-header">Company Key Details</th>
                                                <th colspan="2" class="table-section-header">Application Key Details
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="text-label">Business Name</span></td>
                                                <td>
                                                    <span
                                                        x-text="gaCreditSafe ? (gaCreditSafe.compSum_businessName || '') : ''"></span>
                                                </td>
                                                <td><span class="text-label">Introduction</span></td>
                                                <td>
                                                    <span
                                                        x-text="wpIntroducersInfo ? (wpIntroducersInfo.broker_name || '') : ''"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Company Number</span></td>
                                                <td>
                                                    <span
                                                        x-text="gaCreditSafe ? (gaCreditSafe.compSum_companyRegistrationNumber || '') : ''"></span>
                                                </td>
                                                <td><span class="text-label">Amount Funding</span></td>
                                                <td>
                                                    <span
                                                        x-text="loanInfo?.advance_requested ? `${formatCurrency(loanInfo?.advance_requested)}` : ''"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Registered Address</span></td>
                                                <td>
                                                    <span
                                                        x-text="gaCreditSafe ? (gaCreditSafe.compIdentiBasicInfo_contactAddress_simpleValue || '') : ''"></span>
                                                </td>
                                                <td><span class="text-label">Servicing & Administration Fee</span></td>
                                                <td>
                                                    <span
                                                        x-text="priceModelData?.arrangement_fee_excl_VAT ? `${formatCurrency(priceModelData?.arrangement_fee_excl_VAT)} + VAT` : formatCurrency(0)"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Date Of Incorporation</span></td>
                                                <td>
                                                    <span
                                                        x-text="formatDate(gaCreditSafe?.compIdentiBasicInfo_companyRegistrationDate || '')"></span>
                                                </td>
                                                <td><span class="text-label">Multiple</span></td>
                                                <td>
                                                    <span
                                                        x-text="(priceModelData?.multiple != null && !isNaN(Number(priceModelData.multiple))) ? (Number(priceModelData.multiple).toFixed(2) + 'x') : ''"></span>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Website</span></td>
                                                <td>
                                                    <input type="text" class="input-field company-key-details"
                                                        x-model="wpCommitteePaper.website">
                                                </td>
                                                <td><span class="text-label">Total Repayable</span></td>
                                                <td>
                                                    <span
                                                        x-text="priceModelData ? (priceModelData.total_repayable ? (formatCurrency(priceModelData.total_repayable)) : '') : ''"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle"><span class="text-label">Shareholding &
                                                        Directors</span></td>
                                                <td class="p-0 mb-2">
                                                    <template x-for="(shareHolder, index) in gaCreditSafeShareHolders"
                                                        :key="'shId_' + shareHolder.id">
                                                        <span class="d-block mb-1 px-2"
                                                            x-text="`Shareholders ${index+1}: ${shareHolder.name} (${shareHolder.percent_shares_held}%)`"></span>
                                                    </template>
                                                    <span x-show="directors.length > 0"
                                                        class="d-block border my-2 w-100"></span>
                                                    <template x-for="(director, index) in directors"
                                                        :key="'dire' + director.id">
                                                        <span class="d-block mb-1 px-2"
                                                            x-text="`Director ${index+1}: ${director.name}`"></span>
                                                    </template>
                                                </td>

                                                <td><span class="text-label">Allocation Of The Funds</span></td>
                                                <td>
                                                    <input type="text" class="input-field app-key-details"
                                                        x-model="wpCommitteePaper.allocation_of_the_funds">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Personal Guarantee(s)</span></td>
                                                <td>
                                                    <input type="text" class="input-field company-key-details"
                                                        x-model="wpCommitteePaper.personal_guarantees">
                                                </td>
                                                <td><span class="text-label">IRR</span></td>
                                                <td>
                                                    <template x-if="priceModelData">
                                                        <div>
                                                            <span class="d-block"
                                                                x-text="`Pre Introducer: ${priceModelData.pre_introducer_IRR ? formatCurrency(priceModelData.pre_introducer_IRR) : '0.00'}`">
                                                            </span>
                                                            <span class="d-block"
                                                                x-text="`Post Introducer: ${priceModelData.post_introducer_IRR ? formatCurrency(priceModelData.post_introducer_IRR) : '0.00'}`">
                                                            </span>
                                                        </div>
                                                    </template>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td rowspan="6"><span class="text-label">How They Make Their
                                                        Money</span>
                                                </td>
                                                <td rowspan="6">
                                                    <input type="text" class="input-field company-key-details"
                                                        x-model="wpCommitteePaper.how_they_make_their_money">
                                                </td>
                                                <td><span class="text-label">Why IRR Chosen</span></td>
                                                <td>
                                                    <input type="text" class="input-field app-key-details"
                                                        x-model="wpCommitteePaper.why_irr_chosen">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Duration</span></td>
                                                <td>
                                                    <span
                                                        x-text="priceModelData?.duration ? (priceModelData.duration + ' Weeks') : ''"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">How Long Until Breakeven</span></td>
                                                <td>
                                                    <span
                                                        x-text="priceModelData?.how_long_until_breakeven ? (priceModelData.how_long_until_breakeven + ' Weeks') : ''"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Rate Of Income</span></td>
                                                <td>
                                                    <span
                                                        x-text="priceModelData?.rate_of_income ? priceModelData.rate_of_income + '%' : ''"></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><span class="text-label">Applicable commission % </span></td>
                                                <td>
                                                    <span
                                                        x-text="priceModelData?.commission ? (formatter.format(Number(priceModelData.commission)) + '%') : ''"></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><span class="text-label">Commission payable</span></td>
                                                <td>
                                                    <span x-text="
                                                        (priceModelData?.commission && priceModelData?.advance_requested)
                                                            ? formatCurrency(priceModelData.advance_requested * (priceModelData.commission / 100))
                                                            : formatCurrency(0)
                                                    "></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><span class="text-label">Creditsafe Status</span></td>
                                                <td>
                                                    <span
                                                        x-text="gaCreditSafe ? (gaCreditSafe.compSum_companyStatus_description || '') : ''"></span>
                                                </td>
                                                <td><span class="text-label">Repayment Frequency</span></td>
                                                <td>
                                                    <select class="w-100 border-0 outline-none"
                                                        name="bca_payment_frequency_type"
                                                        x-model="loanInfo.bca_payment_frequency_type_id">
                                                        <option value="">-- Select --</option>
                                                        <template
                                                            x-for="paymentFrequencyType in bcaPaymentFrequencyTypes">
                                                            <option :value="paymentFrequencyType.id"
                                                                :selected="paymentFrequencyType.id == loanInfo.bca_payment_frequency_type_id"
                                                                x-text="paymentFrequencyType.type">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Active CCJ’s</span></td>
                                                <td>
                                                    <template x-if="gaCreditSafeCountyCourtJudgements.length === 0">
                                                        <div class="mb-1">
                                                            <span>None</span>
                                                        </div>
                                                    </template>
                                                    <template
                                                        x-for="(activeCCJ, index) in gaCreditSafeCountyCourtJudgements"
                                                        :key="'activeCCJ_' + activeCCJ.caseNumber">
                                                        <div class="mb-1">
                                                            <span x-show="activeCCJ.court" class="d-block">Court:
                                                                <span x-text="activeCCJ.court"></span>
                                                            </span>
                                                            <span x-show="activeCCJ.ccjDate" class="d-block">Date:
                                                                <span x-text="formatDate(activeCCJ.ccjDate)"></span>
                                                            </span>
                                                            <span x-show="activeCCJ.ccjAmount" class="d-block">Amount:
                                                                <span
                                                                    x-text="formatCurrency(activeCCJ.ccjAmount)"></span>
                                                            </span>
                                                            <span x-show="activeCCJ.caseNumber" class="d-block">Case
                                                                Number:
                                                                <span x-text="activeCCJ.caseNumber"></span>
                                                            </span>
                                                            <span x-show="activeCCJ.ccjStatus" class="d-block">Status:
                                                                <span x-text="activeCCJ.ccjStatus"></span>
                                                            </span>
                                                            <span x-show="activeCCJ.incomingRecordDetails"
                                                                class="d-block">Details: <span
                                                                    x-text="activeCCJ.incomingRecordDetails"></span>
                                                            </span>

                                                            <div class="text-success"
                                                                x-show="index !== gaCreditSafeCountyCourtJudgements.length - 1">
                                                                <hr class="my-2">
                                                            </div>
                                                        </div>

                                                    </template>

                                                </td>
                                                <td><span class="text-label">Repayment Type</span></td>
                                                <td>
                                                    {{-- <select class="w-100 border-0 outline-none"
                                                        name="type_of_entity" x-model="bcaRePaymentTypes.type">
                                                        <option value="">-- Select --</option>
                                                        <option value="fixed">Fixed %</option>
                                                        <option value="receipt">Receipt %</option>
                                                    </select> --}}

                                                    <select class="w-100 border-0 outline-none"
                                                        name="bca_payment_frequency_type"
                                                        x-model="loanInfo.bca_repayment_type_id">
                                                        <option value="">-- Select --</option>
                                                        <template x-for="bcaRepayType in bcaRePaymentTypes">
                                                            <option :value="bcaRepayType.id"
                                                                :selected="bcaRepayType.id == loanInfo.bca_repayment_type_id"
                                                                x-text="bcaRepayType.type">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="text-label">Weighted Scorecard</span></td>
                                                <td>
                                                    <input type="text" class="input-field company-key-details"
                                                        x-model="wpCommitteePaper.weighted_scorecard">
                                                </td>

                                                <td><span class="text-label">Fixed Repayment Amount</span></td>
                                                <td><span
                                                        x-text="priceModelData?.fixed_repayment_amount ? formatCurrency(priceModelData?.fixed_repayment_amount) : ''"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12">
                                <h5 class="table-main-header mb-0 text-center text-center">SECTION TWO: Director and
                                    Homeownership</h5>
                                <div class="responsive-table" style="overflow-x: auto">
                                    <table class="table second-table table-bordered"
                                    style="table-layout: fixed; width: 100%;"
                                    >
                                        <thead>
                                            <tr>
                                                <th class="table-section-header" style="width: 200px;">Directors</th>
                                                <template x-for="(director, index) in directors"
                                                    :key="'director_header_' + index">
                                                    <th colspan="2" class="table-section-header"
                                                        x-text="'Director #' + (index + 1) + ' Information'" style="width: 200px"></th>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <!-- Full Name Row -->
                                                <td class="text-label">Full Name</td>
                                                <template x-for="(director, index) in directors"
                                                    :key="'full_name_' + index">
                                                    <td colspan="2" class="text-value"
                                                        x-text="director.first_name + ' ' + (director.middle_name ? director.middle_name + ' ' : '') + director.surname">
                                                    </td>
                                                </template>
                                            </tr>
                                            <tr>
                                                <!-- Date of Birth Row -->
                                                <td class="text-label">Date Of Birth</td>
                                                <template x-for="(director, index) in directors" :key="'dob_' + index">
                                                    <td colspan="2" class="text-value"
                                                        x-text="formatDate(director.date_of_birth)"></td>
                                                </template>
                                            </tr>
                                            <tr>
                                                <!-- Nationality Row -->
                                                <td class="text-label">Nationality</td>
                                                <template x-for="(director, index) in directors"
                                                    :key="'nationality_' + index">
                                                    <td colspan="2" class="text-value" x-text="director.nationality">
                                                    </td>
                                                </template>
                                            </tr>
                                            <tr>
                                                <!-- CIFAS Return Row -->
                                                <td class="text-label">CIFAS Return</td>
                                                <template x-for="(director, index) in directors"
                                                    :key="'cifas_return_' + index">
                                                    <td colspan="2" class="text-value">
                                                        <input type="text" class="input-field"
                                                            x-model="director.cifas_return">
                                                    </td>
                                                </template>
                                            </tr>
                                            <tr>
                                                <!-- Appointed Date -->
                                                <td class="text-label">Appointed Date</td>
                                                <template x-for="(director, index) in directors"
                                                    :key="'appointed_date' + index">
                                                    <td colspan="2" class="text-value"
                                                        x-text="formatDate(director.date_appointed)"></td>
                                                </template>
                                            </tr>
                                            <tr>
                                                <!-- Transunion Row -->
                                                <td class="text-label">Transunion</td>
                                                <template x-for="(director, index) in directors"
                                                    :key="'transunion_' + index">
                                                    <td colspan="2" class="text-value">
                                                        <input type="text" class="input-field"
                                                            x-model="director.transunion">
                                                    </td>
                                                </template>
                                            </tr>
                                            <tr>
                                                <!-- Home Address Row -->
                                                <td class="text-label">Home Address</td>
                                                <template x-for="(director, index) in directors"
                                                    :key="'home_address_' + index">
                                                    <td colspan="2" class="text-value"
                                                        x-text="director.address_simple_value">
                                                    </td>
                                                </template>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="table-main-header mb-0 text-center text-center">Assets</h5>
                                <div class="table-responsive" style="overflow-x: auto">
                                    <table class="table first-table table-bordered"
                                        style="table-layout: fixed; width: 100%;">
                                        <thead></thead>
                                        <tbody>
                                            <template x-for="(director, index) in directors"
                                                :key="'home_address_' + index">
                                                <tr>
                                                    <th style="width: 150px; max-width: 300px;">
                                                        <span class="text-label"
                                                            x-text="`Director ${index + 1}`"></span>
                                                    </th>
                                                    <td x-data="{
                                                        totalEstimatedValue: 0,
                                                        calculateTotal(assets) {
                                                            return assets.reduce((total, asset) => total + (parseFloat(asset.estimated_value) || 0), 0);
                                                        }
                                                    }"
                                                    x-init="totalEstimatedValue = calculateTotal(director.properties_and_other_assets)">
                                                        <template x-for="(asset, i) in director.properties_and_other_assets" :key="`sop_assets_${asset.id}`">
                                                            <div class="mb-1">
                                                                <span class="fw-bolder mb-1" x-text="`Property ${i + 1}`"></span>
                                                                <span x-text="asset.property_address_and_assets ? ', ' + asset.property_address_and_assets : ''"></span>
                                                                <span x-text="asset.estimated_value ? (asset.property_address_and_assets ? ', Value :' : '') + formatCurrency(asset.estimated_value) : ''"></span>
                                                            </div>
                                                        </template>
                                                        
                                                        <div x-show="totalEstimatedValue > 0" class="mb-1">
                                                            <span class="fw-bold"x-text="`Total Estimated Value : `"></span> <span x-text="formatCurrency(totalEstimatedValue)"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="table-main-header mb-0 text-center text-center">SECTION THREE: Cashflows and
                                    Payment Behaviour</h5>
                                <div class="table-responsive" style="overflow-x: auto">
                                    <table class="table first-table table-bordered"
                                        style="table-layout: fixed; width: 100%;">
                                        <thead></thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Cashflow Source</span></td>
                                                <td>
                                                    <select class="w-100 border-0 outline-none" name="type_of_entity">
                                                        <option value="">-- Select --</option>
                                                        <option value="Open Banking">Open Banking</option>
                                                        <option value="Bank Statements">Bank Statements</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Avg. Monthly Revenue</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Avg. Operating Before
                                                        Tax</span></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Avg. Operating After Tax</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Known Lenders Involved</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Bounced Payments</span></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Tax Repayments</span></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Bank Balance Overdraft</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"><span class="text-label">Key Takeaways</span></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <button @click.prevent="saveData()" type="button" class="btn btn-custom get-loan-data">
                                    <span class="spinner-border spinner-border-sm processing-show"
                                        :class="loadingStates.saveData ? '' : 'd-none'" role="status"
                                        aria-hidden="true"></span>
                                    <span class="processing-show"
                                        :class="loadingStates.saveData ? '' : 'd-none'">Fetching...</span>
                                    <span class="default-show"
                                        :class="loadingStates.saveData ? 'd-none' : ''">Save</span>
                                </button>

                                <button @click.prevent="exportButton()" type="button"
                                    class="btn btn-custom get-loan-data">
                                    <span class="spinner-border spinner-border-sm processing-show"
                                        :class="loadingStates.exportDoc ? '' : 'd-none'" role="status"
                                        aria-hidden="true"></span>
                                    <span class="processing-show"
                                        :class="loadingStates.exportDoc ? '' : 'd-none'">Loading...</span>
                                    <span class="default-show" :class="loadingStates.exportDoc ? 'd-none' : ''">Export
                                        Word Doc</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="committeePaperModal" tabindex="-1" aria-labelledby="committeePaperLabelModal"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="committeePaperLabelModal">Loan Information</h1>
                        <button type="button" @click.prevent="getLoanDetails()" class="btn-close"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="loanTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Loan Number</th>
                                        <th>Advance Requested</th>
                                        <th>Loan Purpose</th>
                                        <th>Deal Status</th>
                                        <th>Funded Date</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('home') }}" type="button" class="btn btn-custom">Home</a>
                        <button @click.prevent="getLoanDetails()" type="button" :disabled="!selectedLoan"
                            class="btn btn-custom btn-show-loading get-loan-data">
                            <span class="spinner-border spinner-border-sm processing-show"
                                :class="loadingStates.onModel ? '' : 'd-none'" role="status" aria-hidden="true"></span>
                            <span class="processing-show"
                                :class="loadingStates.onModel ? '' : 'd-none'">Fetching...</span>
                            <span class="default-show" :class="loadingStates.onModel ? 'd-none' : ''">Get Details</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('css-lib')
<link rel="stylesheet" href="{{ asset('css/sop.css') }}">
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-lib')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="{{ asset('js/committee-paper.js') }}"></script>
@endsection