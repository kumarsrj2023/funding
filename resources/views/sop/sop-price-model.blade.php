@extends('index')
@section('title', Helper::getSiteTitle('Price Model'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">Pricing Model</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'Pricing Model' }}</h4>
        </div>
    </div>
</div>
<div class="price-model" x-data="repaymentCalculator({{ json_encode($priceModelData) }})">
    <div class="">
        <div class="card">
            <div class="card-body">
                @if(!empty($id))
                @include('businesses.add.nav', ['id' => $id])
                @endif
                <div class="mb-3 price-model-table">
                    <div class="row">
                        <div class="col-12 text-end mb-1">
                            <button class="btn btn-custom" @click="submitData" id="submit-data" data-id="{{ $id }}">Save
                                Data</button>
                        </div>
                        <!-- Repayment Structure -->
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-header">
                                            <th colspan="2">Repayment Structure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="tr-body-header">
                                            <td>Description</td>
                                            <td>Calculation</td=>
                                        </tr>
                                        <tr class="highlight-sky">
                                            <td>Advance requested</td>
                                            <td>
                                                <input type="text" class="repayment-input show-money"
                                                    x-model="repaymentStructure.advanceRequested"
                                                    @focus="$el.value = stripCurrency($el.value)"
                                                    @blur="$el.value = formatCurrency($el.value)"
                                                    @input="repaymentStructure.advanceRequested = stripCurrency($el.value)" />
                                            </td>
                                        </tr>
                                        <tr class="highlight-sky">
                                            <td>Repayment period (Months)</td>
                                            <td>
                                                <input type="text" class="repayment-input"
                                                    x-model="repaymentStructure.repaymentPeriodMonths" />
                                            </td>
                                        </tr>
                                        <tr class="highlight-sky">
                                            <td>Multiple</td>
                                            <td>
                                                <input type="text" class="repayment-input"
                                                    x-model="repaymentStructure.multiple" />
                                            </td>
                                        </tr>
                                        <tr class="highlight-sky">
                                            <td>Property Equity (if applicable)</td>
                                            <td>
                                                <input type="text" class="repayment-input show-money"
                                                    x-model="repaymentStructure.propertyEquity"
                                                    @focus="$el.value = stripCurrency($el.value)"
                                                    @blur="$el.value = formatCurrency($el.value)"
                                                    @input="repaymentStructure.propertyEquity = stripCurrency($el.value)" />
                                            </td>
                                        </tr>
                                        <tr class="highlight-sky">
                                            <td>Refinance?</td>
                                            <td x-data="{
                                                fields: [
                                                    { key: 'yes', label: 'Yes' },
                                                    { key: 'no', label: 'No' }
                                                ],}">
                                                <select class="repayment-input border-0"
                                                    x-model="repaymentStructure.refinance">
                                                    <template x-for="(field, index) in fields" :key="index">
                                                        <option :value="field.key"
                                                            :selected="repaymentStructure.refinance === field.key"
                                                            x-text="field.label"></option>
                                                    </template>
                                                </select>

                                            </td>

                                        </tr>
                                        <tr>
                                            <td>Repayment period (Weeks)</td>
                                            <td class="animate-update"
                                                x-text="(repaymentStructure.repaymentPeriodWeeks || 0)"></td>
                                        </tr>
                                        <tr>
                                            <td>Repayment period (Days)</td>
                                            <td x-text="repaymentStructure.repaymentPeriodDays || 0"></td>
                                        </tr>
                                        <tr>
                                            <td>Repayment period (Months)</td>
                                            <td x-text="repaymentStructure.repaymentPeriodMonthsCalculated || 0"></td>
                                        </tr>
                                        <tr>
                                            <td>Arrangement (VAT excl.)</td>
                                            <td x-text="formatCurrency(repaymentStructure.arrangementVATExcl || 0)">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Arrangement (VAT incl.)</td>
                                            <td x-text="formatCurrency(repaymentStructure.arrangementVATIncl || 0)">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Principal</td>
                                            <td x-text="formatCurrency(repaymentStructure.principal || 0)"></td>
                                        </tr>
                                        <tr>
                                            <td>Minimum repayment</td>
                                            <td x-text="formatCurrency(repaymentStructure.minRepayment || 0)"></td>
                                        </tr>
                                        <tr>
                                            <td>Maximum repayment</td>
                                            <td x-text="formatCurrency(repaymentStructure.maxRepayment || 0)"> </td>
                                        </tr>
                                        <tr>
                                            <td>FAG Profit</td>
                                            <td x-text="formatCurrency(repaymentStructure.FAGProfit || 0)"></td>
                                        </tr>
                                        <tr>
                                            <td>FAG Profit (broker calc)</td>
                                            <td x-text="formatCurrency(repaymentStructure.FAGProfitBrokerCalc || 0)">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Max discount available</td>
                                            <td x-text="formatCurrency(repaymentStructure.maxDiscountAvailable || 0)">
                                            </td>
                                        </tr>
                                        <tr class="highlight-black">
                                            <td>Exp. Monthly Repayment</td>
                                            <td x-text="formatCurrency(repaymentStructure.expMonthlyRepay || 0)"></td>
                                        </tr>
                                        <tr class="highlight-black">
                                            <td>Exp. Weekly Repayment</td>
                                            <td x-text="formatCurrency(repaymentStructure.expWeeklyRepay || 0)""></td>
                                        </tr>
                                        <tr class=" highlight-black">
                                            <td>Exp. Daily Repayment</td>
                                            <td x-text="formatCurrency(repaymentStructure.expDailyRepay || 0)"></td>
                                        </tr>
                                        <tr>
                                            <td>Exp. Days to Pay</td>
                                            <td x-text="repaymentStructure.expDaysTopay || 0"></td>
                                        </tr>
                                        <tr>
                                            <td>Weeks to Breakeven</td>
                                            <td x-text="repaymentStructure.weeksToBreakeven || 0"></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Application Revenue Checker -->
                        <div class="col-md-4">
                            <div class="column-wrapper">
                                <div class="table-responsive">
                                    <table class="application-revenue table table-bordered">
                                        <thead>
                                            <tr class="table-header">
                                                <th colspan="2">Application Revenue Checker</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Average Monthly Revenue</td>
                                                <td class="highlight-sky">
                                                    <input type="text" class="application-revenue-input show-money"
                                                        x-model="appRevenueCheker.averageMonthlyRevenue" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>% of Revenue as Repayment</td>
                                                <td class="highlight-green"
                                                    x-text="(appRevenueCheker.revenueAsRepayment || 0) + '%'"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Column for Deal Parameters -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-header">
                                                <th colspan="2">Deal Parameters</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Maturity in Months</td>
                                                <td x-text="deal.maturityInMonths || 0"></td>
                                            </tr>
                                            <tr>
                                                <td>Multiple</td>
                                                <td x-text="(deal.multiple || 0) + 'x'"></td>
                                            </tr>
                                            <tr>
                                                <td>Commission % Advance</td>
                                                <td x-text="(deal.commissionAdvance || 0) + '%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Arrangement</td>
                                                <td x-text="(deal.arrangement || 0).toFixed(2) + '%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Repayment</td>
                                                <td x-text="(deal.repayment || 0) + 'x'"></td>
                                            </tr>
                                            <tr>
                                                <td>Gross Profit</td>
                                                <td x-text="(deal.grossProfit || 0) + '%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Commission</td>
                                                <td x-text="'(' +(deal.commission || 0) + '%)'"></td>
                                            </tr>
                                            <tr>
                                                <td>Net Profit</td>
                                                <td x-text="(deal.netProfit || 0) + '%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Net initial Exposure</td>
                                                <td x-text="(deal.netInitialExposure || 0) + 'x'"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Column for Application Checker -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-header">
                                                <th colspan="2">Application Checker</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>% Repayment Under 20%?</td>
                                                <td class="text-uppercase"
                                                    :class="appChecker.repaymentUnder20 ? 'highlight-green' : 'bg-danger'"
                                                    x-text="appChecker.repaymentUnder20"></td>
                                            </tr>
                                            <tr>
                                                <td>IRR Post Introducer >130%?</td>
                                                <td class="text-uppercase"
                                                    :class="appChecker.postIntroducerIRR ? 'highlight-green' : 'bg-danger'"
                                                    x-text="appChecker.postIntroducerIRR"></td>
                                            </tr>
                                            <tr>
                                                <td>Equity Covers Lend?</td>
                                                <td class="text-uppercase"
                                                    :class="appChecker.equityCoversLend ? 'highlight-green' : 'bg-danger'"
                                                    x-text="appChecker.equityCoversLend"></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- NEW Commission Structure -->
                        <div class="col-md-4">
                            <div class="column-wrapper">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-header">
                                                <th colspan="3">NEW Commission Structure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-body-header">
                                                <td>Multiple</td>
                                                <td>Commission</td>
                                            </tr>
                                            <template x-for="(item, index) in commissionStructure" :key="index">
                                                <tr>
                                                    <td x-text="`${item.i.toFixed(2)}x`"></td>
                                                    <td x-text="`${item.j.toFixed(2)}%`"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Repayment Schedule -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-header">
                                                <th>Repayment Schedule</th>
                                                <th>Weekly</th>
                                                <th>Daily</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Number of periods</td>
                                                <td x-text="(repaymentSchedule.weekly.nbrOfPeriod || 0)"></td>
                                                <td x-text="repaymentSchedule.daily.nbrOfPeriod || 0"></td>
                                            </tr>
                                            <tr>
                                                <td>Repayment</td>
                                                <td x-text="(repaymentSchedule.weekly.repayment || 0)+'%'"></td>
                                                <td x-text="(repaymentSchedule.daily.repayment || 0)+'%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Total Repaid</td>
                                                <td x-text="(repaymentSchedule.weekly.totalRepaid || 0)+'%'"></td>
                                                <td x-text="(repaymentSchedule.daily.totalRepaid || 0)+'%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Period Deal IRR</td>
                                                <td x-text="(repaymentSchedule.weekly.periodDealIRR || 0)+'%'"></td>
                                                <td x-text="(repaymentSchedule.daily.periodDealIRR || 0)+'%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Pre-Introducer IRR</td>
                                                <td class="highlight-green"
                                                    x-text="(repaymentSchedule.weekly.preIntroducerIRR || 0)+'%'"></td>
                                                <td class="highlight-green"
                                                    x-text="(repaymentSchedule.daily.preIntroducerIRR || 0)+'%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Period FAG IRR</td>
                                                <td x-text="(repaymentSchedule.weekly.periodFAGIRR || 0)+'%'"></td>
                                                <td x-text="(repaymentSchedule.daily.periodFAGIRR || 0)+'%'"></td>
                                            </tr>
                                            <tr>
                                                <td>Post-Introducer IRR</td>
                                                <td class="highlight-green"
                                                    x-text="(repaymentSchedule.weekly.postIntroducerIRR || 0)+'%'"></td>
                                                <td class="highlight-green"
                                                    x-text="(repaymentSchedule.daily.postIntroducerIRR || 0)+'%'"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Client Repayment Amounts & Introducer Commission<  -->
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="table-header border-0">
                                            <th colspan="100%" class="">The below table can be shared with the
                                                Introducer</th>
                                        </tr>
                                        <tr class="table-header border-0">
                                            <th colspan="100%">Standard Client Repayment Amounts & Introducer
                                                Commission</th>
                                        </tr>

                                        <tr>
                                            <th>Multiple</th>
                                            <template x-for="multiple in commissionStructure" :key="multiple.i">
                                                <td x-text="multiple.i.toFixed(2) + 'x'"></td>
                                            </template>
                                        </tr>

                                        <tr>
                                            <th>Daily Repayment</th>
                                            <template
                                                x-for="(dailyRepayment, index) in introducerCommissionAndRepayAmt.dailyRepayment"
                                                :key="'dailyRepayment_' + index">
                                                <td x-text="'£'+(Object.values(dailyRepayment)[0])"></td>
                                            </template>
                                        </tr>

                                        <tr>
                                            <th>Weekly Repayment</th>
                                            <template
                                                x-for="(weeklyRepay, index) in introducerCommissionAndRepayAmt.weeklyRepayment"
                                                :key="'weeklyRepay_' + index">
                                                <td x-text="formatCurrency(Object.values(weeklyRepay)[0])"></td>
                                            </template>
                                        </tr>

                                        <tr>
                                            <th>Total Repayment</th>
                                            <template
                                                x-for="(totalRepayment, index) in introducerCommissionAndRepayAmt.totalRepayment"
                                                :key="'totalRepayment_' + index">
                                                <td x-text="formatCurrency(Object.values(totalRepayment)[0])"></td>
                                            </template>
                                        </tr>

                                        <tr>
                                            <th>Introducer Commission</th>
                                            <template
                                                x-for="(introducerCommission, index) in introducerCommissionAndRepayAmt.introducerCommission"
                                                :key="'introducerCommission_' + index">
                                                <td x-text="formatCurrency(Object.values(introducerCommission)[0])">
                                                </td>
                                            </template>
                                        </tr>

                                        <tr>
                                            <th>Introducer Fee (%) of Advance</th>
                                            <template
                                                x-for="(introducerFee, index) in introducerCommissionAndRepayAmt.introducerFee"
                                                :key="'introducerFee_' + index">
                                                <td x-text="introducerFee.toFixed(2) + '%'"></td>
                                            </template>
                                        </tr>

                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@section('meta')
<meta name="class-to-open" content="Price-model">
@endsection

@section('css-lib')
<link rel="stylesheet" href="{{ asset('css/sop.css') }}">
<link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('js-lib')
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>

@endsection

@section('js')
<script>
    function repaymentCalculator(priceModelData = {}) {
		priceModelData = priceModelData || {};
		return {
            formatter: new Intl.NumberFormat('en-GB', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }),
            currencySymbol : '£',
			repaymentStructure: {
				advanceRequested: priceModelData.advance_requested || 10000,
				repaymentPeriodMonths: priceModelData.repayment_period || '4.0',
				multiple: priceModelData.multiple || 1.5,
				propertyEquity: priceModelData.property_equity || 250000,
                refinance: priceModelData.refinance || 'no',
				repaymentPeriodWeeks: 0,
				repaymentPeriodDays: 0,
				repaymentPeriodMonthsCalculated: 0,
				arrangementVATExcl: 0,
				arrangementVATIncl: 0,
				principal: 0,
				minRepayment: 0,
				maxRepayment: 0,
				FAGProfit: 0,
				FAGProfitBrokerCalc: 0,
				maxDiscountAvailable: 0,
				expMonthlyRepay: 0,
				expWeeklyRepay: 0,
				expDailyRepay: 0,
				expDaysTopay: 0,
				weeksToBreakeven: 0,
			},

			appRevenueCheker: {
				averageMonthlyRevenue: priceModelData.average_monthly_revenue || 174783,
				revenueAsRepayment: 0,
			},

			deal: {
				maturityInMonths: null,
				multiple: null,
				commissionAdvance: null,
				arrangement: 2.5,
				repayment: null,
				grossProfit: null,
				commission: null,
				netProfit: null,
				netInitialExposure: null
			},

			repaymentSchedule: {
				weekly: {
					nbrOfPeriod: null,
					repayment: null,
					totalRepaid: null,
					periodDealIRR: null,
					preIntroducerIRR: null,
					periodFAGIRR: null,
					postIntroducerIRR: null,
				},
				daily: {
					nbrOfPeriod: null,
					repayment: null,
					totalRepaid: null,
					periodDealIRR: null,
					preIntroducerIRR: null,
					periodFAGIRR: null,
					postIntroducerIRR: null,
				}

			},

			appChecker: {
				repaymentUnder20: null,
				postIntroducerIRR: null,
				equityCoversLend: null
			},

            // Here i = Multiple, J = Commission and K = Profits 
			commissionStructure: [{
					i: 1.20,
					j: 3.00,
					k: 15.00
				},
				{
					i: 1.25,
					j: 3.50,
					k: 14.00
				},
				{
					i: 1.30,
					j: 4.50,
					k: 15.00
				},
				{
					i: 1.35,
					j: 5.50,
					k: 15.71
				},
				{
					i: 1.40,
					j: 6.50,
					k: 16.25
				},
				{
					i: 1.45,
					j: 8.00,
					k: 17.78
				},
				{
					i: 1.50,
					j: 9.50,
					k: 19.00
				},
				{
					i: 1.55,
					j: 11.50,
					k: 20.91
				},
				{
					i: 1.60,
					j: 13.00,
					k: 21.67
				}
			],

			introducerCommissionAndRepayAmt: {
				dailyRepayment: [],
				weeklyRepayment: [],
				totalRepayment: [],
				introducerCommission: [],
				introducerFee: [3.00, 3.50, 4.50, 5.50, 6.50, 8.00, 9.50, 11.50, 13.00]
			},

			calculateTableFields() {
				this.calculateRepayments();
				this.dealParametersCalculator();
				this.repaymentScheduleCalculator();
				this.appRevenueCalculator();
				this.applicationCheckerCalculator();
				this.introducerCommissionAndRepayAmtCalculator();
			},

			calculateRepayments() {
                // Update repayment period in weeks and days
                const weeksPerYear = 52, daysPerYear = 365;
                const repaymentPeriodMonths = parseFloat(this.repaymentStructure.repaymentPeriodMonths);
                this.repaymentStructure.repaymentPeriodWeeks = Math.floor((repaymentPeriodMonths / 12) * weeksPerYear);
                this.repaymentStructure.repaymentPeriodDays = Math.round((weeksPerYear * 5 / 12) * repaymentPeriodMonths);

                // Calculate expDaysToPay
                this.repaymentStructure.expDaysTopay = this.repaymentStructure.repaymentPeriodWeeks * 7;

                // Repayment period months calculated based on expDaysToPay
                this.repaymentStructure.repaymentPeriodMonthsCalculated = parseFloat(((this.repaymentStructure.expDaysTopay / daysPerYear) * 12).toFixed(2));

                // Arrangement fees (VAT exclusive and inclusive)
                const advanceRequested = parseFloat(this.repaymentStructure.advanceRequested);

                if(this.repaymentStructure.refinance == 'yes') {
                    this.repaymentStructure.arrangementVATExcl = 0;
                    this.repaymentStructure.arrangementVATIncl = 0; 
                } else {
                    this.repaymentStructure.arrangementVATExcl = Math.round(advanceRequested * 0.025);
                    this.repaymentStructure.arrangementVATIncl = Math.round(advanceRequested * 0.03);
                }

                // Principal calculation
                const principal = advanceRequested + this.repaymentStructure.arrangementVATIncl;
                this.repaymentStructure.principal = Math.round(principal);

                // Minimum and Maximum repayment based on the principal and multiple
                const multiple = parseFloat(this.repaymentStructure.multiple);
                this.repaymentStructure.minRepayment = Math.round(principal * multiple);
                this.repaymentStructure.maxRepayment = Math.round(principal * multiple);

                // FAG Profit Calculation
                this.repaymentStructure.FAGProfit = Math.round(this.repaymentStructure.maxRepayment - advanceRequested);
                this.repaymentStructure.FAGProfitBrokerCalc = Math.round((advanceRequested * multiple) - advanceRequested);

                // Max Discount Available
                this.repaymentStructure.maxDiscountAvailable = Math.round(this.repaymentStructure.maxRepayment - this.repaymentStructure.minRepayment);

                // Weekly Repayment Calculation
                const weeklyRepayAmount = (advanceRequested + (advanceRequested * 0.03)) * multiple;
                const repaymentWeeks = this.repaymentStructure.repaymentPeriodWeeks;
                this.repaymentStructure.expWeeklyRepay = (weeklyRepayAmount / repaymentWeeks).toFixed(2);

                // Monthly Repayment Calculation
                this.repaymentStructure.expMonthlyRepay = ((weeklyRepayAmount / repaymentWeeks) * weeksPerYear / 12).toFixed(2);

                // Daily Repayment Calculation
                this.repaymentStructure.expDailyRepay = (this.repaymentStructure.expWeeklyRepay / 5).toFixed(2);

                // Expiry Days to Pay
                this.repaymentStructure.expDaysTopay = this.repaymentStructure.repaymentPeriodWeeks * 7;

                // Weeks to Breakeven Calculation
                const breakevenPrincipal = advanceRequested + (advanceRequested * 0.03);
                const totalRepayment = breakevenPrincipal * multiple;
                const weeklyRepayment = totalRepayment / repaymentWeeks;
                const dailyRepayment = weeklyRepayment / 5;
                this.repaymentStructure.weeksToBreakeven = Math.round(breakevenPrincipal / (dailyRepayment * 5));
            },


			appRevenueCalculator() {
				// application revenue checker
				this.appRevenueCheker.revenueAsRepayment = Math.round((this.repaymentStructure.expMonthlyRepay / this.appRevenueCheker.averageMonthlyRevenue) * 100);
			},

			dealParametersCalculator() {
                // Deal maturity and multiple
                this.deal.maturityInMonths = this.repaymentStructure.repaymentPeriodMonths;
                this.deal.multiple = this.repaymentStructure.multiple;

                // Repayment calculation with arrangement percentage
                const arrangementFactor = 1 + (this.deal.arrangement / 100 * 1.2);
                this.deal.repayment = parseFloat((arrangementFactor * this.deal.multiple).toFixed(3));

                // Gross profit calculation, rounded to one decimal
                const grossProfitCalc = (this.deal.repayment - 1 - (0.2 * (this.deal.arrangement / 100)));
                this.deal.grossProfit = Math.round(grossProfitCalc * 1000) / 10;

                // Find the commission advance percentage based on the multiple
                const multipleStr = parseFloat(this.repaymentStructure.multiple).toFixed(2);
                const commissionEntry = this.commissionStructure.find(row => row.i == multipleStr);

                // If a commission structure is found, assign its 'j' value to commissionAdvance
                this.deal.commissionAdvance = commissionEntry ? commissionEntry.j.toFixed(2) : null;

                // Assign commission and calculate net profit
                this.deal.commission = this.deal.commissionAdvance;
                this.deal.netProfit = this.deal.grossProfit - this.deal.commission;

                // Net initial exposure calculation, rounded to two decimal places
                const commissionFactor = 1 + (this.deal.commission / 100);
                this.deal.netInitialExposure = (Math.round((commissionFactor + Number.EPSILON) * 100) / 100).toFixed(2);
            },

			repaymentScheduleCalculator() {
                // Helper function to round values
                const roundToTwoDecimals = (value) => {
                    return Math.round((value + Number.EPSILON) * 100) / 100;
                };

                // Helper function to calculate repayment
                const calculateRepayment = (dealRepayment, numPeriods) => {
                    return roundToTwoDecimals(dealRepayment / numPeriods * 100).toFixed(2);
                };

                // Helper function to calculate total repaid
                const calculateTotalRepaid = (dealRepayment, numPeriods) => {
                    return roundToTwoDecimals((dealRepayment / numPeriods) * 100 * numPeriods);
                };

                const dealRepayment = parseFloat((1 + (this.deal.arrangement / 100 * 1.2)) * this.deal.multiple);

                // Weekly and daily number of periods
                this.repaymentSchedule.weekly.nbrOfPeriod = Math.ceil((parseFloat(this.deal.maturityInMonths) / 12) * 52);
                this.repaymentSchedule.daily.nbrOfPeriod = Math.ceil((52 * 5 / 12) * parseFloat(this.deal.maturityInMonths));

                // Weekly repayment and total repaid
                this.repaymentSchedule.weekly.repayment = calculateRepayment(dealRepayment, this.repaymentSchedule.weekly.nbrOfPeriod);
                this.repaymentSchedule.weekly.totalRepaid = calculateTotalRepaid(dealRepayment, this.repaymentSchedule.weekly.nbrOfPeriod);

                // Daily repayment and total repaid
                this.repaymentSchedule.daily.repayment = calculateRepayment(dealRepayment, this.repaymentSchedule.daily.nbrOfPeriod);
                this.repaymentSchedule.daily.totalRepaid = calculateTotalRepaid(dealRepayment, this.repaymentSchedule.daily.nbrOfPeriod);

                // IRR calculations
                const weeklyRepayment = (dealRepayment / this.repaymentSchedule.weekly.nbrOfPeriod) * 100 / 100;
                const dailyRepayment = (dealRepayment / this.repaymentSchedule.daily.nbrOfPeriod) * 100 / 100;

                const periodDealIRRWeekly = this.rate(this.repaymentSchedule.weekly.nbrOfPeriod, weeklyRepayment, -1) * 100;
                const periodDealIRRDaily = this.rate(this.repaymentSchedule.daily.nbrOfPeriod, dailyRepayment, -1) * 100;

                this.repaymentSchedule.weekly.periodDealIRR = roundToTwoDecimals(periodDealIRRWeekly).toFixed(2);
                this.repaymentSchedule.daily.periodDealIRR = roundToTwoDecimals(periodDealIRRDaily).toFixed(2);

                // Pre-Introducer IRR
                this.repaymentSchedule.weekly.preIntroducerIRR = roundToTwoDecimals(periodDealIRRWeekly * 52).toFixed(2);
                this.repaymentSchedule.daily.preIntroducerIRR = roundToTwoDecimals(periodDealIRRDaily * 52 * 5).toFixed(2);

                // Calculate periodFAGIRR Weekly
                let weeklyRepaymentForFAGIRR = ((1 + (this.deal.arrangement / 100 * 1.2)) * this.deal.multiple) / this.repaymentSchedule.weekly.nbrOfPeriod;
                let commission = this.deal.commission / 100; // G13 (-9.5%)
                let arrangement = this.deal.arrangement / 100; // G10 (2.5%)
                let customValue = -1 - commission - (arrangement * 0.2);

                let periodFAGIRRWeekly = this.rate(
                    this.repaymentSchedule.weekly.nbrOfPeriod,
                    weeklyRepaymentForFAGIRR,
                    customValue
                ) * 100;

                this.repaymentSchedule.weekly.periodFAGIRR = roundToTwoDecimals(periodFAGIRRWeekly);

                // Calculate periodFAGIRR Daily
                let dailyRepaymentForFAGIRR = ((1 + (this.deal.arrangement / 100 * 1.2)) * this.deal.multiple) / this.repaymentSchedule.daily.nbrOfPeriod;

                let periodFAGIRRDaily = this.rate(
                    this.repaymentSchedule.daily.nbrOfPeriod,
                    dailyRepaymentForFAGIRR,
                    customValue
                ) * 100;

                this.repaymentSchedule.daily.periodFAGIRR = roundToTwoDecimals(periodFAGIRRDaily);

                // Post-Introducer IRR
                this.repaymentSchedule.weekly.postIntroducerIRR = roundToTwoDecimals(periodFAGIRRWeekly * 52);
                this.repaymentSchedule.daily.postIntroducerIRR = roundToTwoDecimals(periodFAGIRRDaily * 52 * 5);
            },

			applicationCheckerCalculator() {
				this.appChecker.repaymentUnder20 = (this.appRevenueCheker.revenueAsRepayment < 20) ? true : false;
				this.appChecker.postIntroducerIRR = (this.repaymentSchedule.weekly.postIntroducerIRR > 130) ? true : false;
                this.appChecker.equityCoversLend = parseFloat(this.repaymentStructure.propertyEquity) >= parseFloat(this.repaymentStructure.maxRepayment);
			},

			introducerCommissionAndRepayAmtCalculator() {
				this.introducerCommissionAndRepayAmt.weeklyRepayment = [];
				this.introducerCommissionAndRepayAmt.dailyRepayment = [];
				this.introducerCommissionAndRepayAmt.totalRepayment = [];
				this.introducerCommissionAndRepayAmt.introducerCommission = [];

				this.commissionStructure.forEach(structure => {
					let multiple = structure.i;
					const multipleAndPrincipleValue = multiple * (parseFloat(this.repaymentStructure.advanceRequested) + (this.repaymentStructure.advanceRequested * 0.03));
					const repaymentPeriodMonth = (Math.floor(this.repaymentStructure.repaymentPeriodMonths / 12 * 52));
					const result = multipleAndPrincipleValue / repaymentPeriodMonth;
                    
					this.introducerCommissionAndRepayAmt.weeklyRepayment.push({ [multiple] : result.toFixed(2) });
					// also push the dailyRepayment value
					this.introducerCommissionAndRepayAmt.dailyRepayment.push({ [multiple] : (result / 5).toFixed(2) });
					// also push the totalRepayment value
                    this.introducerCommissionAndRepayAmt.totalRepayment.push({ [multiple] : multipleAndPrincipleValue.toFixed(2) });
				});

                this.introducerCommissionAndRepayAmt.introducerFee.forEach((introducerFee, index) => {
                    if (this.commissionStructure[index]) {
                        const multiple = this.commissionStructure[index].i;
                        const introducerCommission = (parseFloat(this.repaymentStructure.advanceRequested) * introducerFee) / 100;
                        
                        this.introducerCommissionAndRepayAmt.introducerCommission.push({ [multiple]: introducerCommission.toFixed(2) });
                    }
                });

			},

			rate(nper, pmt, pv, fv = 0, type = 0, guess = 0.1) {
				const epsMax = 1e-10; // Maximum error tolerance
				let iterMax = 50; // Maximum iterations

				let y, y0, y1, x0, x1, f = 0,
					i = 0;
				let rate = guess;

				if (Math.abs(rate) < epsMax) {
					y = pv * (1 + nper * rate) + pmt * (1 + rate * type) * nper + fv;
				} else {
					f = Math.pow(1 + rate, nper);
					y = pv * f + pmt * (1 / rate + type) * (f - 1) + fv;
				}

				y0 = pv + pmt * nper + fv;
				y1 = pv * Math.pow(1 + guess, nper) + pmt * (1 / guess + type) * (Math.pow(1 + guess, nper) - 1) + fv;

				x0 = 0;
				x1 = guess;

				while ((Math.abs(y0 - y1) > epsMax) && (i < iterMax)) {
					rate = (y1 * x0 - y0 * x1) / (y1 - y0);
					x0 = x1;
					x1 = rate;

					if (Math.abs(rate) < epsMax) {
						y = pv * (1 + nper * rate) + pmt * (1 + rate * type) * nper + fv;
					} else {
						f = Math.pow(1 + rate, nper);
						y = pv * f + pmt * (1 / rate + type) * (f - 1) + fv;
					}

					y0 = y1;
					y1 = y;
					i++;
				}

				return rate;
			},

			submitData() {
                const self = this;
				let businessId = document.getElementById('submit-data').getAttribute('data-id');

				let dataToSend = {
                    repaymentStructure : self.repaymentStructure,
                    appRevenueCheker : self.appRevenueCheker,
                    deal : self.deal,
                    repaymentSchedule: self.repaymentSchedule,
                    appChecker: self.appChecker,
                    commissionStructure: self.commissionStructure,
                    introducerCommissionAndRepayAmt: self.introducerCommissionAndRepayAmt,
				};

				fetch(window.location.href, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: JSON.stringify(dataToSend),
					})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							Swal.fire({
								icon: 'success',
								title: 'Success!',
								text: data.message,
							});
						} else {
							alert('Something went wrong.');
						}
					})
					.catch(error => {
						console.error('Error:', error);
						alert('An error occurred.');
					});

			},

            formatCurrency(value) {
                if (value === null || value === undefined || isNaN(value)) {
                    return `${this.currencySymbol}0.00`;
                }
                return `${this.currencySymbol}${this.formatter.format(Number(value))}`;
            },
            
            stripCurrency(value) {
                return value.replace(/[^0-9.]/g, '');  // Remove non-numeric characters
            },

            initializeCurrencySigns() {
                const currencyFields = document.querySelectorAll('.show-money');
                const formatter = new Intl.NumberFormat('en-GB', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                currencyFields.forEach((input) => {
                    if (input.value && !input.value.startsWith('£')) {
                        input.value = '£' + formatter.format(Number(input.value));
                    }
                });
            },

			init() {
                window.addEventListener('load', this.initializeCurrencySigns);
               
				this.$watch('repaymentStructure.advanceRequested', () => this.calculateTableFields());
				this.$watch('repaymentStructure.repaymentPeriodMonths', () => this.calculateTableFields());
				this.$watch('repaymentStructure.multiple', () => this.calculateTableFields());
				this.$watch('repaymentStructure.propertyEquity', () => this.calculateTableFields());
				this.$watch('repaymentStructure.refinance', () => this.calculateTableFields());
				this.$watch('appRevenueCheker.averageMonthlyRevenue', () => this.calculateTableFields());
				this.$watch('deal.arrangement', () => this.calculateTableFields());

				// Initial calculation
				this.calculateTableFields();
			}
		};
	}

</script>

@endsection