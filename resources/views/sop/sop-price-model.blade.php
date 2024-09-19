@extends('index')
@section('title', Helper::getSiteTitle('Price Model'))
<style>
    .price-model .table-header {
        background-color: #5a5a5a;
        color: white !important;
        text-align: center;
        font-weight: bold;
    }

    .price-model .tr-body-header {
        background-color: #767575;
    }

    .price-model .tr-body-header td {
        color: #fff;
    }

    .price-model .table-header th {
        color: white !important;

    }

    .price-model .highlight-black {
        background-color: #000;
        color: #fff;
    }

    .price-model .highlight-black td {
        color: #fff;
    }

    .price-model .highlight-black .repayment-input {
        color: #fff;
        background: transparent;
    }

    .price-model .highlight-green .application-revenue-input {
        color: #000;
        background: transparent;
    }

    .price-model .highlight-green .checker-input {
        color: #000;
        background: transparent;
    }

    .price-model .highlight-green .repayment-schedule {
        color: #000;
        background: transparent;
    }

    .price-model .highlight-row {
        background-color: #f8f9fa;
        text-align: center;
        font-weight: bold;
    }

    .price-model .highlight-green {
        background-color: #c6efce !important;
        color: #000;
    }
</style>

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
<div class="price-model">
    <div class="">
        <div class="card">
            <div class="card-body">
                @if(!empty($id))
                @include('businesses.add.nav', ['id' => $id])
                @endif
                <div class="my-3">
                    <div class="row">
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
                                        <tr>
                                            <td>Advance requested</td>
                                            <td><input type="text" class="repayment-input" value="£10,000" /></td>
                                        </tr>
                                        <tr>
                                            <td>Repayment period (Months)</td>
                                            <td><input type="text" class="repayment-input" value="4.00" /></td>
                                        </tr>
                                        <tr>
                                            <td>Multiple</td>
                                            <td><input type="text" class="repayment-input" value="1.50x" /></td>
                                        </tr>
                                        <tr>
                                            <td>Property Equity (if applicable)</td>
                                            <td><input type="text" class="repayment-input" value="£2,50,000" /></td>
                                        </tr>
                                        <tr>
                                            <td>Repayment period (Weeks)</td>
                                            <td><input type="text" class="repayment-input" value="17" /></td>
                                        </tr>
                                        <tr>
                                            <td>Repayment period (Days)</td>
                                            <td><input type="text" class="repayment-input" value="87" /></td>
                                        </tr>
                                        <tr>
                                            <td>Repayment period (Months)</td>
                                            <td><input type="text" class="repayment-input" value="3.91" /></td>
                                        </tr>
                                        <tr>
                                            <td>Arrangement (VAT excl.)</td>
                                            <td><input type="text" class="repayment-input" value="£250" /></td>
                                        </tr>
                                        <tr>
                                            <td>Arrangement (VAT incl.)</td>
                                            <td><input type="text" class="repayment-input" value="£300" /></td>
                                        </tr>
                                        <tr>
                                            <td>Principal</td>
                                            <td><input type="text" class="repayment-input" value="£10,300" /></td>
                                        </tr>
                                        <tr>
                                            <td>Minimum repayment</td>
                                            <td><input type="text" class="repayment-input" value="£15,450" /></td>
                                        </tr>
                                        <tr>
                                            <td>Maximum repayment</td>
                                            <td><input type="text" class="repayment-input" value="£15,450" /></td>
                                        </tr>
                                        <tr>
                                            <td>FAG Profit</td>
                                            <td><input type="text" class="repayment-input" value="£5,450" /></td>
                                        </tr>
                                        <tr>
                                            <td>FAG Profit (broker calc)</td>
                                            <td><input type="text" class="repayment-input" value="£5,000" /></td>
                                        </tr>
                                        <tr>
                                            <td>Max discount available</td>
                                            <td><input type="text" class="repayment-input" value="£0" /></td>
                                        </tr>
                                        <tr class="highlight-black">
                                            <td>Exp. Monthly Repayment</td>
                                            <td><input type="text" class="repayment-input" value="£3,938.24" /></td>
                                        </tr>
                                        <tr class="highlight-black">
                                            <td>Exp. Weekly Repayment</td>
                                            <td><input type="text" class="repayment-input" value="£908.82" /></td>
                                        </tr>
                                        <tr class="highlight-black">
                                            <td>Exp. Daily Repayment</td>
                                            <td><input type="text" class="repayment-input" value="£181.76" /></td>
                                        </tr>
                                        <tr>
                                            <td>Exp. Days to Pay</td>
                                            <td><input type="text" class="repayment-input" value="119" /></td>
                                        </tr>
                                        <tr>
                                            <td>Weeks to Breakeven</td>
                                            <td><input type="text" class="repayment-input" value="11" /></td>
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
                                                <td><input type="text" class="application-revenue-input"
                                                        value="£1,74,783"></td>
                                            </tr>
                                            <tr>
                                                <td>% of Revenue as Repayment</td>
                                                <td class="highlight-green"><input type="text"
                                                        class="application-revenue-input" value="2%"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Column for Deal Parameters -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <!-- Second Table Header: Deal Parameters -->
                                        <thead>
                                            <tr class="table-header">
                                                <th colspan="2">Deal Parameters</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Maturity in Months</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="4.0" /></td>
                                            </tr>
                                            <tr>
                                                <td>Multiple</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="1.50x" /></td>
                                            </tr>
                                            <tr>
                                                <td>Commission % Advance</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="9.50%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Arrangement</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="2.50%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Repayment</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="1.545x" /></td>
                                            </tr>
                                            <tr>
                                                <td>Gross Profit</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="54.0%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Commission</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="(9.5%)" /></td>
                                            </tr>
                                            <tr>
                                                <td>Net Profit</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="44.5%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Net initial Exposure</td>
                                                <td><input type="text" class="deal-input deal-table-input"
                                                        value="1.10x" /></td>
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
                                        <!-- Third Table Body -->
                                        <tbody>
                                            <tr>
                                                <td>% Repayment Under 20%?</td>
                                                <td class="highlight-green"><input type="text"
                                                        class="checker-input checker-table-input" value="TRUE" /></td>
                                            </tr>
                                            <tr>
                                                <td>IRR Post Introducer >130%?</td>
                                                <td class="highlight-green"><input type="text"
                                                        class="checker-input checker-table-input" value="TRUE" /></td>
                                            </tr>
                                            <tr>
                                                <td>Equity Covers Lend?</td>
                                                <td class="highlight-green"><input type="text"
                                                        class="checker-input checker-table-input" value="TRUE" /></td>
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
                                                <td>% Profits</td>
                                            </tr>
                                            <tr>
                                                <td>1.20x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="3.00%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="15.00%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.25x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="3.50%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="14.00%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.30x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="4.50%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="15.00%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.35x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="5.50%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="15.71%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.40x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="6.50%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="16.25%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.45x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="8.00%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="17.78%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.50x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="9.50%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="19.00%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.55x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="11.50%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="20.91%" /></td>
                                            </tr>
                                            <tr>
                                                <td>1.60x</td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="13.00%" /></td>
                                                <td><input type="text" class="commission-input commission-table-input"
                                                        value="21.67%" /></td>
                                            </tr>
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
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="18" /></td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="87" /></td>
                                            </tr>
                                            <tr>
                                                <td>Repayment</td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="8.58%" /></td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="1.78%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Total Repaid</td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="154.50%" /></td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="154.50%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Total Paid</td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="100.00%" /></td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="100.00%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Period Deal IRR</td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="5.04%" /></td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="1.08%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Pre-Introducer IRR</td>
                                                <td class="highlight-green"><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="262.23%" /></td>
                                                <td class="highlight-green"><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="279.74%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Period FAG IRR</td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="3.85%" /></td>
                                                <td><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="0.82%" /></td>
                                            </tr>
                                            <tr>
                                                <td>Post-Introducer IRR</td>
                                                <td class="highlight-green"><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="200.18%" /></td>
                                                <td class="highlight-green"><input type="text"
                                                        class="repayment-schedule repayment-schedule-input"
                                                        value="214.10%" /></td>
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
                                        <tr class="table-header">
                                            <th>Multiple</th>
                                            <th>1.20x</th>
                                            <th>1.25x</th>
                                            <th>1.30x</th>
                                            <th>1.35x</th>
                                            <th>1.40x</th>
                                            <th>1.45x</th>
                                            <th>1.50x</th>
                                            <th>1.55x</th>
                                            <th>1.60x</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th class="highlight-row">Daily Repayment</th>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£145.41" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£151.47" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£157.53" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£163.59" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£169.65" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£175.71" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£181.76" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£187.82" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£193.88" /></td>
                                        </tr>
                                        <tr>
                                            <th class="highlight-row">Weekly Repayment</th>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£727.06" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£757.35" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£787.65" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£817.94" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£848.24" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£878.53" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£908.82" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£939.12" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£969.41" /></td>
                                        </tr>
                                        <tr>
                                            <th class="highlight-row">Total Repayment</th>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£12,360.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£12,875.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£13,390.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£13,905.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£14,420.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£14,935.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£15,450.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£15,965.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£16,480.00" /></td>
                                        </tr>
                                        <tr>
                                            <th class="highlight-row">Introducer Commission</th>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£300.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£350.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£450.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£550.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£650.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£800.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£950.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£1,150.00" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="£1,300.00" /></td>
                                        </tr>
                                        <tr>
                                            <th class="highlight-row">Introducer Fee (%) of Advance</th>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="3.00%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="3.50%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="4.50%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="5.50%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="6.50%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="8.00%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="9.50%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="11.50%" /></td>
                                            <td><input type="text" class="repayment-input commission-table-input"
                                                    value="13.00%" /></td>
                                        </tr>
                                    </tbody>
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
@endsection