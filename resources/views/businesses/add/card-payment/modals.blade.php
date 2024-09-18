<div class="modal fade" id="payment-form-{{ isset($data) && !empty($data) ? 'edit' : 'add' }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">{{ isset($data) && !empty($data) ? 'Update Card Payment ' : 'Add New Card Payment ' }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ isset($data) && !empty($data) ? route('businesses.card.payment.add', [$id, $data->id]) : route('businesses.card.payment.add', $id) }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
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
                                            $list = [1 => 'Single Payment', 2 => 'Recurring Payment'];
                                        @endphp
                                        <label class="form-label" for="{{ $index }}">Payment Type </label>
                                        <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                            @foreach($list as $key => $item)
                                                <option value="{{ $key }}" {{ isset($data) && !empty($data) && Helper::getInputValue($index, $data) == $key ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-recurring-payment-fields {{ isset($data) && !empty($data) && $data->payment_type == 2 ? '' : 'd-none' }}">
                                        <div class="mb-2">
                                            @php $index = 'installments'; @endphp
                                            <label for="{{ $index }}" class="form-label">Number of Payments </label>
                                            <input type="number" min="1" name="{{ $index }}" class="form-control {{ $index }}" id="{{ $index }}" value="{{ isset($data) && !empty($data) ? $data->installments : '' }}">
                                        </div>
                                        <div class="mb-2">
                                            @php 
                                                $index = 'recurring_payment';
                                                $list = ['weekly' => 'Weekly', 'daily' => 'Daily'];
                                            @endphp
                                            <label class="form-label" for="{{ $index }}">Recurring Interval </label>
                                            <select class="form-select {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}" id="{{ $index }}">
                                                @foreach($list as $key => $item)
                                                    <option value="{{ $key }}" {{ isset($data) && !empty($data) && Helper::getInputValue($index, $data) == $key ? 'selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="recurring_payment_day_of_week mb-2 {{ isset($data) && !empty($data) && $data->recurring_payment == 'daily' ? 'd-none' : '' }}">
                                            @php 
                                                $index = 'recurring_payment_day_of_week';
                                                $list = [2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday', 1 => 'Sunday'];
                                                $values = isset($data) && !empty($data) && !empty($data->recurring_payment_day_of_week) ? json_decode($data->recurring_payment_day_of_week, true) : [];
                                            @endphp
                                            <label class="form-label {{ $index }}" for="{{ $index }}">Payment Day of Week </label>
                                            <select class="form-select select2 {{ $errors->has($index) ? 'is-invalid' : '' }}" name="{{ $index }}[]" id="{{ $index }}" multiple>
                                                @foreach($list as $key => $item)
                                                    <option value="{{ $key }}" {{ !empty($values) && in_array($key, $values) ? 'selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            @php $index = 'recurring_payment_time'; @endphp
                                            <label for="{{ $index }}" class="form-label">Payment Time </label>
                                            <input type="time" name="{{ $index }}" class="form-control {{ $index }}" id="{{ $index }}" value="{{ isset($data) && !empty($data) ? $data->recurring_payment_time : '18:00:00' }}">
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

