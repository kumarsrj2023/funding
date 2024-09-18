<div class="modal fade" id="director-export" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Export directors </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('businesses.director.info.export', $id) }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-2">
                        <p class="mb-0">Use <strong>ID</strong> column in order to import and update director without creating duplicate entries</p>
                        @include('includes.show-message')
                        <h6 class="fs-15">Export as</h6>
                        <div class="form-check form-checkbox-dark">
                            <input type="radio" id="file-type-csv" name="type" value="csv" class="form-check-input" checked>
                            <label class="form-check-label" for="file-type-csv">Plain CSV file</label>
                        </div>
                        <div class="form-check form-checkbox-dark">
                            <input type="radio" id="file-type-xlsx" name="type" value="xlsx" class="form-check-input">
                            <label class="form-check-label" for="file-type-xlsx">XLSX spreadsheet</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal">Cancel</a>
                    <button class="btn btn-custom btn-ajax-show-processing" type="submit">
                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                        <span class="processing-show d-none">Exporting...</span>
                        <span class="default-show">Export directors </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>