<div class="modal fade" id="transaction-form-{{ isset($data) && !empty($data) ? 'edit' : 'add' }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">{{ isset($data) && !empty($data) ? 'Update Transaction' : 'Add New Transaction' }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ isset($data) && !empty($data) ? route('transaction.form.update', $data->id) : route('transaction.form.add') }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
                <div class="modal-body p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @php $index = 'business_unit'; @endphp
                                        <label for="{{ $index }}" class="form-label">Business Unit</label>
                                        <select class="form-select {{ $index }} business-unit" id="{{ $index }}" name="{{ $index }}">
                                            <option value="BCA">BCA</option>
                                            <option value="SIF">SIF</option>
                                            <option value="OPS">OPS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @php $index = 'debtor_id'; @endphp
                                        <label for="{{ $index }}" class="form-label">Debtor ID</label>
                                        <select class="form-select {{ $index }}" id="{{ $index }}" name="{{ $index }}">
                                            @php 
                                                for ($i = 0; $i < 1000; $i++) 
                                                { 
                                                    $val = $i;

                                                    if ($val < 10) 
                                                    {
                                                        $val = '00' . $val;
                                                    }
                                                    elseif ($val >= 10 && $val < 100) 
                                                    {
                                                        $val = '0' . $val;
                                                    }

                                                    echo '<option value="' . $val . '">' . $val . '</option>';
                                                }
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @php $index = 'transaction_id'; @endphp
                                        <label for="{{ $index }}" class="form-label">Transaction ID</label>
                                        <select class="form-select item-disabled {{ $index }}" id="{{ $index }}" name="{{ $index }}" disabled>
                                            @php 
                                                for ($i = 0; $i < 1000; $i++) 
                                                { 
                                                    $val = $i;

                                                    if ($val < 10) 
                                                    {
                                                        $val = '00' . $val;
                                                    }
                                                    elseif ($val >= 10 && $val < 100) 
                                                    {
                                                        $val = '0' . $val;
                                                    }

                                                    echo '<option value="' . $val . '">' . $val . '</option>';
                                                }
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @php
                                            $index = 'category';

                                            $categories = DB::table('ga_ledger_categories')->orderBy('category', 'asc')->get();
                                        @endphp
                                        <label for="{{ $index }}" class="form-label">Category</label>
                                        <select class="form-select {{ $index }}" id="{{ $index }}" name="{{ $index }}">
                                            <option value="">Select Category</option>
                                            @if(!$categories->isEmpty())
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->category }}" {{ isset($data) && Helper::getInputValue($index, $data) == $category->category ? 'selected' : '' }}>{{ $category->category }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @php $index = 'comments'; @endphp
                                        <label for="{{ $index }}" class="form-label">Comments</label>
                                        <textarea placeholder="Comments" name="{{ $index }}" class="form-control {{ $index }}" rows="5" id="{{ $index }}"></textarea>
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

