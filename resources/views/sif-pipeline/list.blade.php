@extends('index')
@section('title', Helper::getSiteTitle('SIF Pipeline'))

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Businesses</a></li>
                    <li class="breadcrumb-item active">SIF Pipeline</li>
                </ol>
            </div>
            <h4 class="page-title">{{ !empty($business_info) && !empty($business_info->business_name) ?
                $business_info->business_name : 'SIF Pipeline' }}</h4>
        </div>
    </div>
</div>
<div class="form-wrapper sif-pipeline" id="sifPipeline">
    <form id="sifPipelineForm" action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            @include('includes.show-message', ['extra_class' => 'col-12 mb-2'])
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('includes.pipeline-navbar')
                        <div
                            x-data="sifMilestoneFilter({{ json_encode($response['bcaMilestoneStages']) }}, {{ json_encode($response['nestedLoans']) }}, {currentUrl: '{{ URL::current() }}'}, {{ json_encode($response['sifMilestoneStages']) }}, {{ json_encode($response['bcaSubStages']) }}, {{ json_encode($response['sifSubStages']) }})">
                            <div class="filter-container row">
                                <!-- Milestone Filter -->
                                <div class="col-12 mb-3 col-md-3 position-relative" x-ref="milestone-filter">
                                    <label for="milestoneFilter" class="form-label">Filter by Milestones:</label>
                                    <button @click.prevent="toggleMilestoneDropdown" class="form-select w-full">
                                        <span x-show="selectedMilestones.length === 0" class="">Select Milestones</span>
                                        <span x-show="selectedMilestones.length > 0" class=""
                                            x-text="`Milestone (${selectedMilestones.length})`"></span>
                                    </button>

                                    <div x-cloak x-show="isMilestoneDropdownOpen"
                                        @click.away="isMilestoneDropdownOpen = false"
                                        class="filter-data-result position-absolute">
                                        <template x-for="(stage, index) in sifMilestoneStages" :key="index">
                                            <label :class="{
                                                    'selected': selectedMilestones.includes(stage.milestone)
                                                }"
                                                class="d-flex justify-content-start align-items-center px-1 py-2 w-100 fw-normal">
                                                <input type="checkbox" :value="stage.milestone"
                                                    class="form-checkbox mx-2"
                                                    :checked="selectedMilestones.includes(stage.milestone)"
                                                    @change="toggleMilestone(stage.milestone)">
                                                <span x-text="stage.milestone"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>

                                <!-- Analyst Filter -->
                                <div class="col-12 mb-3 col-md-3">
                                    <label for="analystFilter" class="form-label">Filter by Underwriter:</label>
                                    <select class="form-select" x-model="selectedAnalyst" @change="filterLoans">
                                        <option value="">Select Analyst</option>
                                        <template x-for="analyst in underWriter" :key="analyst.id">
                                            <option :value="analyst.member_name" x-text="analyst.member_name"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Clear Filter Button -->
                                <div class="col-12 mb-3 col-md-3 align-self-end" x-cloak
                                    x-show="selectedMilestones.length > 0 || selectedAnalyst">
                                    <button class="btn btn-secondary" @click.prevent="clearFilters">Clear
                                        Filters</button>
                                </div>
                            </div>

                            <div id="milestoneDataContainer">
                                <template x-for="(milestone, index) in filteredLoans" :key="index">
                                    <div class="row">
                                        <div class="card-header d-sm-flex align-items-center justify-content-between">
                                            <h4 class="header-title mb-1 mb-sm-0" x-text="`${index}`"></h4>
                                            <div
                                                class="search-box position-relative d-flex justify-content-between align-items-center">
                                                <input type="text" class="form-control w-sm-auto"
                                                    placeholder="Enter Business name"
                                                    x-model="milestoneSearchQueries[index]" @input="filterLoans()">
                                                <span @click="clearSearchQuery(index)">
                                                    <x-icon-x-mark />
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body compact-card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <div class="table-responsive">
                                                        <div class="mb-3">
                                                            <table class="table table-bordered w-100 milestone-table"
                                                                x-data="{thead : ['Milestone', 'Sub-Milestone' , 'Loan Amount', 'Underwriter' , 'Introducer', 'Description', 'Business Name', 'Action' ]}">
                                                                <thead>
                                                                    <tr>
                                                                        <template x-for="head in thead" :key="head">
                                                                            <th class="text-start t-head-th"
                                                                                :class="head.replace(' ','-').toLowerCase()"
                                                                                x-text="head"></th>
                                                                        </template>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <template
                                                                        x-for="(loan, i) in filterLoansByBusinessName(milestone.loans, index)"
                                                                        :key="'loan_'+i">
                                                                        <tr>
                                                                            <td class="text-start"
                                                                                x-text="loan.milestone">
                                                                            </td>
                                                                            <td class="text-start"
                                                                                x-text="loan.sub_milestone">
                                                                            </td>
                                                                            <td class="text-start"
                                                                                x-text="loan.advance_requested">
                                                                            </td>
                                                                            <td class="text-start"
                                                                                x-text="loan.analyst">
                                                                            </td>
                                                                            <td class="text-start"
                                                                                x-text="loan?.introducer || ''">
                                                                            </td>
                                                                            <td class="text-start"
                                                                                x-text="loan?.description || ''">
                                                                            </td>
                                                                            <td class="text-start">
                                                                                <a :href="'{{ route('businesses.customer.info', ':id') }}'.replace(':id', loan.business_id)"
                                                                                    title="Customer info"
                                                                                    x-text="loan.business_name"></a>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="btn btn-custom"
                                                                                    data-bs-toggle="modal"
                                                                                    @click="openModalWithData(loan)"
                                                                                    :data-bs-target="`#updateDealPipeline-${loan.id}`">
                                                                                    Update
                                                                                </button>
                                                                                <div class="">
                                                                                    @include('sif-pipeline.modal-deal-pipeline')
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </template>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>
</div>
@endsection

@section('meta')
<meta name="class-to-open" content="deal-pipeline">
@endsection

@section('css-lib')
<link rel="stylesheet" href="{{ asset('css/sop.css') }}">
@endsection

@section('js-lib')
<script src="//unpkg.com/alpinejs" defer></script>
<script src="{{ asset('js/x-main.js') }}"></script>
@endsection

@section('js')
<script>
    window.appData = {
            committee_members: @json(DB::table('committee_members')->orderBy('member_name','asc')->get()),
        };        
</script>
@endsection