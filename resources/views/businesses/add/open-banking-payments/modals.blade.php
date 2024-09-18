<div class="modal fade" id="open-banking-payments-form-{{ isset($data) && !empty($data) ? 'edit' : 'add' }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">{{ isset($data) && !empty($data) ? 'Update Open Banking Payments ' : 'Add New Open Banking Payments ' }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ isset($data) && !empty($data) ? route('businesses.open.banking.payments.add', [$id, $data->id]) : route('businesses.open.banking.payments.add', $id) }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
                <div class="modal-body p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @php $index = 'amount'; @endphp
                                        <label for="{{ $index }}" class="form-label">Amount </label>
                                        <input type="number" min="0" step="0.01" name="{{ $index }}" class="form-control {{ $index }}" id="{{ $index }}" placeholder="0.00" value="{{ isset($data) && !empty($data) ? $data->amount : '' }}">
                                    </div>
                                    <div class="mb-2">
                                        @php 
                                            $index = 'payment_type';
                                            $list = ['single_payment' => 'Single Payment', 'recurring_payment' => 'Recurring Payment'];
                                        @endphp
                                        <label class="form-label" for="{{ $index }}">Payment Type </label>
                                        <select class="form-select ob_payment_type {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            @foreach($list as $key => $item)
                                                <option value="{{ $item }}" {{ isset($data) && !empty($data) && (Helper::getInputValue($index, $data) == $key || Helper::getInputValue($index, $data) == $item) ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-recurring-payment-fields {{ isset($data) && !empty($data) && ($data->payment_type == 'recurring_payment' || $data->payment_type == 'Recurring Payment') ? '' : 'd-none' }}">
                                        <div class="mb-2">
                                            @php $index = 'payment_reference'; @endphp
                                            <label for="{{ $index }}" class="form-label">Payment Reference </label>
                                            <input type="text" name="{{ $index }}" class="form-control {{ $index }}" id="{{ $index }}" value="{{ isset($data) && !empty($data) ? $data->payment_reference : '' }}">
                                        </div>
                                        <div class="mb-2">
                                            @php 
                                                $index = 'recurring_payment';
                                                $list = ['weekly' => 'Weekly', 'monthly' => 'Monthly'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Recurring Interval </label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                @foreach($list as $key => $item)
                                                    <option value="{{ $key }}" {{ isset($data) && !empty($data) && Helper::getInputValue($index, $data) == $key ? 'selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            @php 
                                                $index = 'recurring_start_day';
                                                $list = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Start Day </label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                @foreach($list as $item)
                                                    <option value="{{ $item }}" {{ isset($data) && !empty($data) && Helper::getInputValue($index, $data) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            @php $index = 'recurring_expiry_date'; @endphp
                                            <label for="{{ $index }}" class="form-label">Payment Time </label>
                                            <input type="date" name="{{ $index }}" class="form-control {{ $index }}" id="{{ $index }}" value="{{ isset($data) && !empty($data) ? $data->recurring_expiry_date : '' }}">
                                        </div>
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

