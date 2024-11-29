<div>
    @php
    $businessInfo = $response['businessInfo'] ?? [];
    $loanInfo = $response['loanInfo'] ?? [];
    $wpIntroducersInfo = $response['wpIntroducersInfo'] ?? [];
    $bcaPaymentFrequencyTypes = $response['bcaPaymentFrequencyTypes'] ?? [];
    $gaCreditSafe = $response['gaCreditSafe'] ?? [];
    $gaCreditSafeShareHolders = $response['gaCreditSafeShareHolders'] ?? [];
    $gaCreditSafeCountyCourtJudgements = $response['gaCreditSafeCountyCourtJudgements'] ?? [];
    $priceModelData = $response['priceModelData'] ?? [];
    $directors = $response['directors'] ?? [];
    $bcaRePaymentTypes = $response['bcaRePaymentTypes'] ?? [];


    @endphp
    <!-- Assets and Liabilities Details -->
    <div class="" style="margin-bottom: 1rem;">
        <table style="border-collapse: collapse; width: 100%; border: 1px solid rgb(26, 51, 95); table-layout: fixed">
            <thead>
                <tr style="border: none; border-bottom: 1px solid gray;">
                    <td colspan="4"
                        style="background-color: #003366 !important; text-align: center; padding: 1rem 0px;">
                        <h5 class="table-main-header"
                            style="color: white; font-size: 16px; text-align: center; margin-bottom: 0;">
                            SECTION ONE: Company and Application Details
                        </h5>
                    </td>
                </tr>
                <tr>
                    <th colspan="2"
                        style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; text-align: center;">
                        Company Key Details
                    </th>
                    <th colspan="2"
                        style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; text-align: center;">
                        Application Key Details
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Business Name
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ $gaCreditSafe->compSum_businessName ?? '' }}
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Introduction
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ $wpIntroducersInfo->broker_name ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Company Number
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ $gaCreditSafe->compSum_companyRegistrationNumber ?? '' }}
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Amount Funding
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($loanInfo->advance_requested) ? '£' . $loanInfo->advance_requested : '' }}
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Registered Address
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ $gaCreditSafe->compIdentiBasicInfo_contactAddress_simpleValue ?? '' }}
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Servicing & Administration Fee
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($priceModelData->arrangement_fee_excl_VAT) ? ('£' . $priceModelData->arrangement_fee_excl_VAT . ' + VAT') : '' }}
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Date Of Incorporation
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ isset($gaCreditSafe->compIdentiBasicInfo_companyRegistrationDate) ?
                        \Carbon\Carbon::parse($gaCreditSafe->compIdentiBasicInfo_companyRegistrationDate)->format('d-m-Y')
                        : '' }}
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Multiple
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($priceModelData->multiple) ? number_format((float)$priceModelData->multiple, 2, '.',
                        '') . 'x' : '' }}
                    </td>
                </tr>

                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Website
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ $businessInfo->website_address ?? '' }}
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Total Repayable
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($priceModelData->total_repayable) ? '£' .
                        number_format((float)$priceModelData->total_repayable, 2, '.', '') : '' }}
                    </td>
                </tr>

                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Shareholding & Directors
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        @if(is_array($gaCreditSafeShareHolders) && count($gaCreditSafeShareHolders) > 0)
                        @foreach ($gaCreditSafeShareHolders as $index => $shareHolder)
                        <p style="margin-top: 0px; margin-bottom: 4px;">
                            Director {{ $index + 1 }}: {{ $shareHolder->name }} ({{
                            $shareHolder->percent_shares_held}}%)
                        </p>
                        @endforeach
                        @endif
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Allocation Of The Funds
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        N/A
                    </td>

                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Personal Guarantee(s)
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        N/A
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        IRR
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        <p style="margin-top: 0px; margin-bottom: 4px;">
                            Pre Introducer: £{{ $priceModelData->pre_introducer_IRR ?? '0.00' }}
                        </p>

                        <p style="margin-top: 0px; margin-bottom: 4px;">
                            Post Introducer: £{{ $priceModelData->post_introducer_IRR ?? '0.00' }}
                        </p>
                    </td>

                </tr>
                <tr>
                    <td rowspan="4"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        How They Make Their Money
                    </td>
                    <td rowspan="4" style="padding: 8px; border: 1px solid;">
                        N/A
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Why IRR Chosen
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        N/A
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Duration
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($priceModelData->duration) ? $priceModelData->duration . ' Weeks' :
                        '' }}
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        How Long Untill Breakeven
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($priceModelData->how_long_until_breakeven) ?
                        ($priceModelData->how_long_until_breakeven . '  Weeks') : '' }}
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Rate Of Income
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($priceModelData->rate_of_income) ? ($priceModelData->rate_of_income . '%') : '' }}
                    </td>

                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Creditsafe Status
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ $gaCreditSafe->compSum_companyStatus_description ?? '' }}
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Repayment Frequency
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ $bcaPaymentFrequencyTypes->type ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Active CCJ’s
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        @if(is_array($gaCreditSafeCountyCourtJudgements) && count($gaCreditSafeCountyCourtJudgements) >
                        0)
                        @foreach ($gaCreditSafeCountyCourtJudgements as $index => $ccj)
                        <div class="mb-1">
                            @if($ccj->court)
                            <span style="display:block;">Court: {{ $ccj->court }}</span>
                            @endif

                            @if($ccj->ccjDate)
                            @php
                            $formattedDate = date('d-m-Y',strtotime($ccj->ccjDate));
                            @endphp
                            <span style="display:block;">Date: {{ $formattedDate }}</span>
                            @endif

                            @if($ccj->ccjAmount)
                            <span style="display:block;">Amount: {{ $ccj->ccjAmount }}</span>
                            @endif

                            @if($ccj->caseNumber)
                            <span style="display:block;">Case Number: {{ $ccj->caseNumber }}</span>
                            @endif

                            @if($ccj->ccjStatus)
                            <span style="display:block;">Status: {{ $ccj->ccjStatus }}</span>
                            @endif

                            @if($ccj->incomingRecordDetails)
                            <span style="display:block;">Details: {{ $ccj->incomingRecordDetails }}</span>
                            @endif

                            @if($index !== count($gaCreditSafeCountyCourtJudgements) - 1)
                            <div class="text-success">
                                <hr class="my-2">
                            </div>
                            @endif
                        </div>
                        @endforeach
                        @endif
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Repayment Type
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($bcaRePaymentTypes->type) ? $bcaRePaymentTypes->type : '' }}
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Weighted Scorecard
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        N/A
                    </td>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Fixed Repayment Amount
                    </td>
                    <td style="padding: 8px; border: 1px solid;">
                        {{ !empty($priceModelData->fixed_repayment_amount) ? ('£' . $priceModelData->fixed_repayment_amount) :
                        '' }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="" style="margin-bottom: 1rem;">
        <div
            style="background-color: #003366 !important; text-align: center; padding: 1rem 0; border-bottom: 1px solid gray;">
            <h5 class="table-main-header" style="color: white; font-size: 16px; text-align: center; margin: 0;">
                SECTION TWO: Director and Homeownership
            </h5>
        </div>
        <table style="border-collapse: collapse; width: 100%; border: 1px solid rgb(26, 51, 95);">
            <thead>
                <tr>
                    <th
                        style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; text-align: center;">
                    </th>
                    @foreach ($directors as $index => $director)
                    <th colspan="2"
                        style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; text-align: center;">
                        {{ 'Director #' . ($index + 1) . ' Information' }}
                    </th>
                    @endforeach

                </tr>
            </thead>

            <tbody>
                <!-- Full Name Row -->
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Full Name
                    </td>
                    @foreach ($directors as $index => $director)
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $director->first_name . ' ' . ($director->middle_name ?? '') . ' ' . $director->surname }}
                    </td>
                    @endforeach
                </tr>

                <!-- Date of Birth Row -->
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Date Of Birth
                    </td>
                    @foreach ($directors as $index => $director)
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ \Carbon\Carbon::parse($director->date_of_birth)->format('d-m-Y') }}
                    </td>
                    @endforeach
                </tr>

                <!-- Nationality Row -->
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Nationality
                    </td>
                    @foreach ($directors as $index => $director)
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $director->nationality }}
                    </td>
                    @endforeach
                </tr>

                <!-- CIFAS Return Row -->
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        CIFAS Return
                    </td>
                    @foreach ($directors as $index => $director)
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $director->CIFASReturn ?? '' }} </td>
                    @endforeach
                </tr>

                <!-- CIFAS Return Row -->
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        CIFAS Return
                    </td>
                    @foreach ($directors as $index => $director)
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ \Carbon\Carbon::parse($director->date_appointed)->format('d-m-Y') }}
                    </td>
                    @endforeach
                </tr>


                <!-- Transunion Row -->
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Transunion
                    </td>
                    @foreach ($directors as $index => $director)
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $director->transunion ?? '' }} </td>
                    @endforeach
                </tr>

                <!-- Home Address Row -->
                <tr>
                    <td
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Home Address
                    </td>
                    @foreach ($directors as $index => $director)
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $director->address_street }}
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>