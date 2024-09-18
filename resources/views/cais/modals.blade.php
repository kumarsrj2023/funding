<div class="modal fade" id="cais-form-{{ isset($data) && !empty($data) ? 'edit' : 'add' }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Add Business</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cais.file.add') }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
                <div class="modal-body p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @php $index = 'add_reg_number'; @endphp
                                        <label for="{{ $index }}" class="form-label">Registration No: </label>
                                        <input type="text" name="{{ $index }}" class="form-control {{ $index }}" id="{{ $index }}" placeholder="Reg No.">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-inline-block w-100">
                    <button class="btn btn-custom btn-ajax-show-processing float-right" type="submit">
                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                        <span class="processing-show d-none">Saving...</span>
                        <span class="default-show">Save</span>
                    </button>
                    <a href="javascript:void(0);" class="btn btn-light float-right" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

