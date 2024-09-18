<div class="modal fade" id="categories-import" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Import categories by spreadsheet</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.import') }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="mb-1">
                            <div class="alert alert-danger mb-2" role="alert">
                                Uploaded sheet must be in the same format and same heading columns as in the sample file.
                            </div>
                            @php $index = 'file'; @endphp
                            <input type="file" id="{{ $index }}" name="{{ $index }}" class="form-control {{ $index }}">
                            <p class="input-definition-text mt-1 mb-0">Upload excel sheet only (required XLSX or CSV)</p>
                        </div>
                        <div class="mt-1 file-uploader d-none">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-inline-block w-100">
                    <a href="{{ asset('files/categories.xlsx') }}" class="download-sample-file text-success d-inline-block" download="">Download sample file</a>
                    <button class="btn btn-custom btn-ajax-show-processing float-right" type="submit">
                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                        <span class="processing-show d-none">Uploading...</span>
                        <span class="default-show">Upload</span>
                    </button>
                    <a href="javascript:void(0);" class="btn btn-light float-right" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="categories-export" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Export categories</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.export') }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-2">
                        <p class="mb-0">Use <strong>ID</strong> column in order to import and update category without creating duplicate entries</p>
                        @include('dashboard.includes.show-message')
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
                        <span class="default-show">Export categories</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>