<!-- Unique modal for each row -->
<div class="modal fade" :id="`updateDealPipeline-${loan.id}`" tabindex="-1" aria-labelledby="updateDealPipelineLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-header">
                <h1 class="modal-title fs-5" id="updateDealPipelineLabel" x-text="modalData.business_name"></h1>
                <button @click="modalData = {}; errors.errorList = {}; errors.isError = false;" type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card no-lt-rt-pad">
                    <div class="card-body compact-card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="loan_type" x-text="'Loan Type'"></label>
                                    <select @change="loadMilestones(modalData.loan_type)" class="form-select"
                                        name="loan_type" id="loan_type" x-model="modalData.loan_type">
                                        <option value="">Select Loan type</option>
                                        <option value="BCA"
                                            :selected="modalData.loan_type === 'BCA' || modalData.loan_type === 'bca'">
                                            BCA
                                        </option>
                                        <option value="SIF"
                                            :selected="modalData.loan_type === 'SIF' || modalData.loan_type === 'sif'">
                                            SIF
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-12" x-data="{section: 'analyst'}">
                                <div class="mb-3">
                                    <label class="form-label" :for="section" x-text="'Underwriter'"></label>
                                    <select class="form-select" :class="section" :name="section"
                                        x-model="modalData.analyst">
                                        <option value="" x-text="`Select Underwriter`"></option>

                                        <template x-for="(member, underwriterIndex) in underWriter"
                                            :key="`underwriter_${underwriterIndex}`">
                                            <option :value="member.member_name"
                                                :selected="modalData.analyst === member.member_name"
                                                x-text="member.member_name">
                                            </option>
                                        </template>

                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="loan_type" x-text="'Milestone'"></label>
                                    <select @change="filterSIFSubMilestones(modalData.milestone)" class="form-select"
                                        name="milestone" :value="modalData.milestone" x-model="modalData.milestone">
                                        <option value="">Select Milestone</option>

                                        <template x-for="(milestone, indexMilestone) in milestones"
                                            :key="'milestone_' + indexMilestone">
                                            <option :value="milestone.milestone" x-text="milestone.milestone"
                                                :selected="modalData.milestone === milestone.milestone">
                                            </option>
                                        </template>

                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="loan_type" x-text="'Sub-Milestone'"></label>
                                    <select class="form-select" name="sub_stage" x-model="modalData.sub_milestone">
                                        <option value="">Select Sub-Milestone</option>

                                        <template x-for="(milestone, indexMilestone) in subMilestones"
                                            :key="'milestone_' + indexMilestone">
                                            <option :value="milestone.sub_milestone" x-text="milestone.sub_milestone"
                                                :selected="modalData.sub_milestone === milestone.sub_milestone">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12" x-data="{section: 'description'}">
                                <div class="mb-3">
                                    <label class="form-label" :id="section" :for="section"
                                        x-text="'Description'"></label>
                                    <textarea class="form-control" rows="4" :class="section" :name="section"
                                        x-model="modalData.description"></textarea>
                                </div>
                            </div>

                            <div x-show="errors.isError" id="displayMessages" class="display-messages col-12 mb-2">
                                <div class="alert alert-danger" role="alert">
                                    <template x-for="(error, index) in errors.errorList" :key="index">
                                        <p class="mb-1" x-text="error"></p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="modalData = {}; errors.errorList = {}; errors.isError = false;" type="button"
                    class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button @click="saveChanges()" type="button" class="btn btn-custom btn-show-loading get-loan-data">
                    <span class="spinner-border spinner-border-sm processing-show"
                        :class="loadingStates.onModel ? '' : 'd-none'" role="status" aria-hidden="true"></span>
                    <span class="processing-show" :class="loadingStates.onModel ? '' : 'd-none'">Saving...</span>
                    <span class="default-show" :class="loadingStates.onModel ? 'd-none' : ''">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>