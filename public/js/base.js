var table = '';
var typingTimer = '';
var doneTypingInterval = 500;

try {
    Dropzone.autoDiscover = false;
} catch (err) {}

$(document).ready(function () {

    openNavigation();

    $(document).on('select2:open', () => {

        document.querySelector('.select2-search__field').focus();
    });

    if ($(".select2").length) {
        $(".select2").select2({
            width: "100%",
            placeholder: $(".select2").data('placeholder'),
        });
    }

    if ($(".select2-tags").length) {
        $(".select2-tags").select2({
            width: "100%",
            tags: true
        });
    }

    if ($(".datetime-datepicker").length) {
        $(".datetime-datepicker").flatpickr({
            enableTime: !0,
            dateFormat: "Y-m-d H:i"
        });
    }

    if ($(".select2-ajax-request-load").length && $(".select2-ajax-request-load").data('url')) {
        var tag = false;

        if ($(".select2-ajax-request-load").data('tag') == 1) {
            tag = true;
        }

        $(".select2-ajax-request-load").select2({
            minimumInputLength: 2,
            tags: tag,
            ajax: {
                url: $(".select2-ajax-request-load").data('url'),
                dataType: 'json',
                delay: 500,
                type: "GET",
                quietMillis: 50,
                data: function (term) {
                    return {
                        term: term
                    };
                },
                processResults: function (data) {

                    return {
                        results: $.map(data.results, function (obj) {

                            return {
                                id: obj.id,
                                text: obj.name
                            };
                        })
                    };
                }
            }
        });
    }

    if ($('#dz-upload-image').length) {
        $('#dz-upload-image').dropzone({
            url: $('#dz-upload-image').data("action"),
            paramName: 'dz_image',
            acceptedFiles: 'image/*',
            previewsContainer: $('#dz-upload-image').data("previewsContainer"),
            previewTemplate: $('#uploadPreviewTemplate').html(),
            uploadMultiple: false,
            addedfiles: function () {

                if ($("#dz-upload-image").parents('form').find('.btn-show-processing').length) {
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').addClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').prop('disabled', true);
                }

                if ($("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').length) {
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').addClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').prop('disabled', true);
                }
            },
            success: function (file, response) {

                $(file.previewElement).find('.dz-image-id').val(response.id);
                $(file.previewElement).find('.dz-remove-btn').attr('data-id', response.id);

                if ($("#dz-upload-image").parents('form').find('.btn-show-processing').length) {
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').prop('disabled', false);
                }

                if ($("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').length) {
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').prop('disabled', false);
                }
            },
            removedfile: function (file) {

                if ($("#dz-upload-image").parents('form').find('.btn-show-processing').length) {
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-show-processing').prop('disabled', false);
                }

                if ($("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').length) {
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').removeClass('disabled');
                    $("#dz-upload-image").parents('form').find('.btn-ajax-show-processing').prop('disabled', false);
                }

                var url = $(file.previewElement).find('.dz-remove-btn').data('url');
                var id = $(file.previewElement).find('.dz-remove-btn').data('id');

                if (url && id) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function (data) {}
                    });
                }

                file.previewElement.remove();

                return true;
            }
        });
    }

    if ($(".summernote").length) {
        $(".summernote").summernote({
            enterHtml: '<p></p>',
            height: 250,
            callbacks: {
                onInit: function (e) {
                    $(e.editor).find(".custom-control-description").addClass("custom-control-label").parent().removeAttr("for");
                },
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            },
        });
    }

    try {
        createTable();
    } catch (err) {}

    // by suraj
    $("body").on("click", "#openModalBtn", function () {
        if (!$.fn.DataTable.isDataTable('.datatable-sop')) {
            sopModelTable();
        }
    });

    if ($("#limit_date_range").val() == 'NO') {
        $("#date_range_is_fixed").addClass('item-disabled');
        $("#date_range_is_fixed").prop('disabled', true);
        $("#start_date").addClass('item-disabled');
        $("#start_date").prop('disabled', true);
        $("#end_date").addClass('item-disabled');
        $("#end_date").prop('disabled', true);
        $("#last_number_of_days").addClass('item-disabled');
        $("#last_number_of_days").prop('disabled', true);
    } else {
        $("#date_range_is_fixed").removeClass('item-disabled');
        $("#date_range_is_fixed").prop('disabled', false);
        $("#start_date").removeClass('item-disabled');
        $("#start_date").prop('disabled', false);
        $("#end_date").removeClass('item-disabled');
        $("#end_date").prop('disabled', false);

        if ($("#date_range_is_fixed").val() == 'NO') {
            $("#start_date").addClass('item-disabled');
            $("#start_date").prop('disabled', true);
            $("#end_date").addClass('item-disabled');
            $("#end_date").prop('disabled', true);
            $("#last_number_of_days").removeClass('item-disabled');
            $("#last_number_of_days").prop('disabled', false);
        } else {
            $("#start_date").removeClass('item-disabled');
            $("#start_date").prop('disabled', false);
            $("#end_date").removeClass('item-disabled');
            $("#end_date").prop('disabled', false);
            $("#last_number_of_days").addClass('item-disabled');
            $("#last_number_of_days").prop('disabled', true);
        }
    }

    $("body").on('change', '#group_transaction', function (e) {

        if ($(this).val() && $(this).val() == 'NO') {
            $(".additional-companies").addClass('d-none');
        } else {
            $(".additional-companies").removeClass('d-none');
        }
    });

    $("body").on('change', '#open_banking', function (e) {

        if ($(this).val() && $(this).val() == 'YES') {
            if (!$('#deal_date_div').hasClass('d-none')) {
                $("#deal_date_div").addClass('d-none');
            }
        } else if ($(this).val() && $(this).val() == 'NO') {
            if ($('#deal_date_div').hasClass('d-none')) {
                $("#deal_date_div").removeClass('d-none');
            }
        }
    });

    $("body").on('change', '#limit_date_range', function (e) {

        if ($(this).val() == 'NO') {
            $("#date_range_is_fixed").addClass('item-disabled');
            $("#date_range_is_fixed").prop('disabled', true);
            $("#start_date").addClass('item-disabled');
            $("#start_date").prop('disabled', true);
            $("#end_date").addClass('item-disabled');
            $("#end_date").prop('disabled', true);
            $("#last_number_of_days").addClass('item-disabled');
            $("#last_number_of_days").prop('disabled', true);
        } else {
            $("#date_range_is_fixed").removeClass('item-disabled');
            $("#date_range_is_fixed").prop('disabled', false);
            $("#start_date").removeClass('item-disabled');
            $("#start_date").prop('disabled', false);
            $("#end_date").removeClass('item-disabled');
            $("#end_date").prop('disabled', false);

            if ($("#date_range_is_fixed").val() == 'NO') {
                $("#start_date").addClass('item-disabled');
                $("#start_date").prop('disabled', true);
                $("#end_date").addClass('item-disabled');
                $("#end_date").prop('disabled', true);
                $("#last_number_of_days").removeClass('item-disabled');
                $("#last_number_of_days").prop('disabled', false);
            } else {
                $("#start_date").removeClass('item-disabled');
                $("#start_date").prop('disabled', false);
                $("#end_date").removeClass('item-disabled');
                $("#end_date").prop('disabled', false);
                $("#last_number_of_days").addClass('item-disabled');
                $("#last_number_of_days").prop('disabled', true);
            }
        }
    });

    $("body").on('change', '#date_range_is_fixed', function (e) {

        if ($(this).val() == 'NO') {
            $("#start_date").addClass('item-disabled');
            $("#start_date").prop('disabled', true);
            $("#end_date").addClass('item-disabled');
            $("#end_date").prop('disabled', true);
            $("#last_number_of_days").removeClass('item-disabled');
            $("#last_number_of_days").prop('disabled', false);
        } else {
            $("#start_date").removeClass('item-disabled');
            $("#start_date").prop('disabled', false);
            $("#end_date").removeClass('item-disabled');
            $("#end_date").prop('disabled', false);
            $("#last_number_of_days").addClass('item-disabled');
            $("#last_number_of_days").prop('disabled', true);
        }
    });

    $("body").on('change', '#ownership', function (e) {

        if ($(this).val() == 'Home Owner (Own Home)' || $(this).val() == 'Home Owner (Investment Property)') {
            $(".home-owner").removeClass('d-none');
        } else {
            $(".home-owner").addClass('d-none');
        }
    });

    $("body").on('change', '#proof_of_address', function (e) {

        if ($(this).val() == 'council_tax') {
            $(".council-tax").removeClass('d-none');
            $(".bank-statement").addClass('d-none');
        } else if ($(this).val() == 'bank_statement') {
            $(".council-tax").addClass('d-none');
            $(".bank-statement").removeClass('d-none');
        } else {
            $(".council-tax").addClass('d-none');
            $(".bank-statement").addClass('d-none');
        }
    });

    $('body').on('change', '.select-all-checkbox', function (e) {

        if (this.checked) {
            if ($('.select-item-checkbox').length) {
                if ($(".bulk-order-status-change").length) {
                    $(".bulk-order-status-change").val('').change();
                    $(".bulk-order-status-change").removeClass('d-none');
                }

                $(".remove-all-button").removeClass('d-none');
                $(".select-item-checkbox").prop('checked', true);
            }
        } else {
            if ($(".bulk-order-status-change").length) {
                $(".bulk-order-status-change").val('').change();
                $(".bulk-order-status-change").addClass('d-none');
            }

            $(".remove-all-button").addClass('d-none');
            $(".select-item-checkbox").prop('checked', false);
        }
    });

    // by suraj
    $('body').on('change', '.select-sop-item-checkbox', function () {
        var anyChecked = $(".select-sop-item-checkbox:checked").length > 0;

        if (anyChecked) {
            $(".send-sop").removeClass('d-none');
        } else {
            $(".send-sop").addClass('d-none');
        }
    });

    // by suraj
    $('body').on('change', '.select-all-checkbox-for-sop', function (e) {

        if (this.checked) {
            if ($('.select-sop-item-checkbox').length) {
                $(".send-sop").removeClass('d-none');
                $(".select-sop-item-checkbox").prop('checked', true);
            }
        } else {
            $(".send-sop").addClass('d-none');
            $(".select-sop-item-checkbox").prop('checked', false);
        }
    });

    // by suraj
    $('body').on('click', '.send-sop', function (e) {

        var ids = [];

        $(".select-sop-item-checkbox:checked").each(function () {
            ids.push($(this).val());
        });

        ids = ids.join();



        var url = $(this).attr('data-send-sop-url');

        if (ids && url) {
            sendSOP($(this), ids, url);
        }
    });

    // by suraj
    function sopModelTable() {
        if (!$('.datatable-sop').length) {
            return;
        }

        let sopUrl = $('#sopModel').data('sop-url');

        $('.datatable-sop').DataTable({
            "pageLength": 15,
            "scrollX": false,
            "ordering": false,
            "lengthChange": true,
            "searching": true,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "language": {
                "emptyTable": "{{ __('No result found') }}",
                "search": ''
            },
            "layout": {
                topStart: {
                    search: {
                        placeholder: 'Search...'
                    }
                },
                topEnd: function () {
                    return '';
                }
            },
            "ajax": {
                url: sopUrl,
                data: function (d) {
                    if ($(".sorting-filter-menu").length) {
                        d.sort_by = $(".sorting-filter-menu input:checked").val();
                    }
                }
            },
            "columnDefs": [{
                    className: "index-colum-checkbx no-rowurl-redirect",
                    "targets": [0]
                },
                {
                    className: "text-left w-45",
                    "targets": [1]
                },
                {
                    className: "text-left",
                    "targets": [2]
                },
                {
                    className: "text-left",
                    "targets": [3]
                },
            ],
            "columns": [{
                    "data": "actions",
                    "name": "index_data"
                },
                {
                    "data": "name",
                    "name": "name"
                },
                {
                    "data": "email",
                    "name": "email"
                },
                {
                    "data": "address_simple_value",
                    "name": "address_simple_value"
                }
            ]

        });
    }

    // by suraj
    function sendSOP(_this, id, url, redirect = '') {

        var title = 'Are you sure?';
        var text = 'You will not be able to revert this!';
        var icon = 'warning';
        var confirm_button_color = '#3085d6';
        var cancel_button_color = '#d33';
        var confirm_button_text = 'Yes, Send It!';

        if (_this.data('title')) {
            title = _this.data('title');
        }

        if (_this.data('text')) {
            text = _this.data('text');
        }

        if (_this.data('icon')) {
            icon = _this.data('icon');
        }

        if (_this.data('confirmbuttoncolor')) {
            confirm_button_color = _this.data('confirmbuttoncolor');
        }

        if (_this.data('cancelbuttoncolor')) {
            cancel_button_color = _this.data('cancelbuttoncolor');
        }

        if (_this.data('confirmbuttontext')) {
            confirm_button_text = _this.data('confirmbuttontext');
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: confirm_button_color,
            cancelButtonColor: cancel_button_color,
            confirmButtonText: confirm_button_text,
            preConfirm: function (t) {

                return fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: 'id=' + id,
                    }).then(function (t) {
                        if (!t.ok) throw new Error(t.statusText);

                        if (redirect) {
                            location.reload();
                        } else {
                            refreshSOPTable();
                        }
                        return t.json();
                    })
                    .catch(function (t) {
                        Swal.showValidationMessage("Request failed. Please refresh the page and try again")
                    });
            }
        });
    }

    $('body').on('change', '.select-item-checkbox', function (e) {

        if ($('.select-item-checkbox:checked').length) {
            if ($(".bulk-order-status-change").length) {
                $(".bulk-order-status-change").val('').change();
                $(".bulk-order-status-change").removeClass('d-none');
            }

            $(".remove-all-button").removeClass('d-none');
        } else {
            if ($(".bulk-order-status-change").length) {
                $(".bulk-order-status-change").val('').change();
                $(".bulk-order-status-change").addClass('d-none');
            }

            $(".remove-all-button").addClass('d-none');
        }

        if (!$(".select-item-checkbox:not(:checked)").length) {
            $(".select-all-checkbox").prop('checked', true);
        } else {
            $(".select-all-checkbox").prop('checked', false);
        }
    });

    $('body').on('click', '.remove-all-button', function (e) {

        var ids = [];

        $(".select-item-checkbox:checked").each(function () {
            ids.push($(this).val());
        });

        ids = ids.join();

        var url = $('meta[name="remove-url"]').attr('content');
        if (ids && url) {
            removeItemData($(this), ids, url);
        }
    });

    $('body').on('click', '.remove-item-button', function (e) {

        var id = $(this).data('id');
        var url = $('meta[name="remove-url"]').attr('content');

        if (id && url) {
            removeItemData($(this), id, url);
        }
    });

    $('body').on('click', '.remove-item-button-direct', function (e) {

        var id = $(this).data('id');
        var url = $(this).data('url');

        if (id && url) {
            removeItemData($(this), id, url);
        }
    });

    $("body").on('keyup paste change', '.search-in-datatables', function () {

        clearTimeout(typingTimer);
        var _this = $(this);
        typingTimer = setTimeout(function () {
            refreshTable();
        }, doneTypingInterval);
    });

    $("body").on('change', '.sorting-filter-menu input', function () {

        refreshTable();
    });

    $('body').on('click', '.btn-show-processing', function (e) {

        buttonLoading($(this), 1, 0);
    });

    $('body').on('submit', '.ajax-form-submit', function (e) {

        e.preventDefault();

        var _this = $(this);
        var button = $(this).find(".btn-ajax-show-processing");

        clearValidationErrors(_this);
        buttonLoading(button);

        var form_data = new FormData(this);
        var url = $(this).attr('action');

        $.ajax({
            xhr: function () {
                if ($('input[type="file"]').val()) {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener("progress", updateProgress, false);
                    return xhr;
                } else {
                    return new XMLHttpRequest();
                }
            },
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                if ($(".file-uploader").length) {
                    $(".file-uploader").addClass('d-none');
                }

                if (data.status < 1) {

                    buttonLoading(button, 0);

                    if (data.error_message) {
                        if (_this.find(".display-messages").length) {
                            _this.find(".display-messages").html('<div class="alert alert-danger" role="alert">' + data.error_message + '</div>');
                            _this.find(".display-messages").slideDown('slow');
                        }
                    } else if (data.swal_error_message) {
                        buttonLoading(button, 0);

                        Swal.fire({
                            title: "Failed",
                            text: data.swal_error_message,
                            icon: "danger",
                            preConfirm: function (t) {
                                location.reload();
                            }
                        });
                    } else if (data.message) {
                        $.each(data.message, function (key, val) {

                            if (_this.find('.' + key).length) {
                                _this.find('.' + key).addClass('is-invalid');

                                $.each(val, function (field_key, field_val) {
                                    _this.find('.' + key).after('<div class="invalid-feedback">' + field_val + '</div>');
                                });
                            }
                        });
                    }
                } else if (data.status && data.status == 1) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.redirect_stop) {
                        if (!data.no_reset) {
                            _this[0].reset();
                        }

                        buttonLoading(button, 0);

                        if (_this.parents('.modal').length && !data.no_hide_modal) {
                            modal_id = _this.parents('.modal').attr('id');
                            $('#' + modal_id).find('.btn-close').click();
                        }

                        try {
                            refreshTable();
                        } catch (err) {}

                        window.location.href = data.redirect_stop;
                    } else if (data.refresh) {
                        location.reload();
                    } else if (data.swal_message) {
                        buttonLoading(button, 0);

                        if (!data.no_reset) {
                            _this[0].reset();
                        }

                        if (_this.parents('.modal').length) {
                            modal_id = _this.parents('.modal').attr('id');
                            $('#' + modal_id).find('.btn-close').click();
                        }

                        try {
                            refreshTable();
                        } catch (err) {}

                        if (data.swal_title) {
                            var swal_title = data.swal_title;
                        } else {
                            var swal_title = 'Success';
                        }

                        Swal.fire({
                            title: swal_title,
                            text: data.swal_message,
                            icon: "success",
                            preConfirm: function (t) {

                                try {
                                    refreshTable();
                                } catch (err) {
                                    location.reload();
                                }
                            }
                        });
                    } else {
                        if (!data.no_reset) {
                            _this[0].reset();
                        }

                        if (_this.parents('.modal').length) {
                            modal_id = _this.parents('.modal').attr('id');
                            $('#' + modal_id).find('.btn-close').click();
                        }

                        clearValidationErrors(_this);

                        try {
                            refreshTable();
                        } catch (err) {}

                        buttonLoading(button, 0);

                        if (data.message) {
                            var c = {
                                heading: "Action",
                                text: data.message,
                                position: "top-right",
                                loaderBg: "#fff",
                                icon: "info",
                                hideAfter: 5000,
                                stack: 1
                            };
                            $.toast(c);
                        }
                    }
                } else {
                    buttonLoading(button, 0);
                }
            }
        });
    });

    $('body').on('click', '.fetch-dynamic-modal', function (e) {

        var _this = $(this);
        var url = $(this).data('url');

        if (url) {
            $.ajax({
                url: url,
                type: 'GET',
                data: {},
                success: function (data) {
                    if (data.status && data.status == 1 && data.modal) {
                        if ($(".dynamic-page-modals").length) {
                            $(".dynamic-page-modals").html(data.modal);

                            if ($(".dynamic-page-modals").find('.modal').length) {
                                modal_id = $(".dynamic-page-modals").find('.modal').attr('id')

                                $('#' + modal_id).modal('toggle');
                                $('#' + modal_id).modal('show');

                                if ($('#' + modal_id).find('select').hasClass('select2')) {
                                    $('#' + modal_id).find('select').select2({
                                        width: "100%",
                                        dropdownParent: $('#' + modal_id)
                                    });
                                }
                            }
                        }
                    }
                }
            });
        }
    });

    $('body').on('change keyup', 'input', function (e) {

        clearValidationErrors($(this).parent());
    });

    $('body').on('click', '.insert-repeater', function (e) {

        var repeaterclass = $(this).data('repeaterclass');

        if (repeaterclass) {
            var html = $("." + repeaterclass + " .node:first-child").clone();
            $("." + repeaterclass).append(html);
            $("." + repeaterclass + " .node:last-child").find('input').val('').change();
            $("." + repeaterclass + " .node:last-child").find('select').val('').change();

            if ($("." + repeaterclass + " .node:last-child").find('select').hasClass('select2')) {
                $("." + repeaterclass + " .node:last-child").find('select').select2({
                    width: "100%"
                });
            }
        }
    });

    $('body').on('click', '.delete-repeater-node', function (e) {

        $(this).parents('.node').find('input').val('');
        $(this).parents('.node').find('select').val('').change();

        if ($(this).parents('.repeater').find('.node').length > 1) {
            $(this).parents('.node').remove();
        }
    });

    $('body').on('change', '#payment_type', function (e) {

        if ($(this).val() == 2) {
            $('.card-recurring-payment-fields').removeClass('d-none');
        } else {
            $('.card-recurring-payment-fields').addClass('d-none');
        }
    });

    $('body').on('change', '#recurring_payment', function (e) {

        if ($(this).val() == 'daily') {
            $('.recurring_payment_day_of_week').addClass('d-none');
        } else {
            $('.recurring_payment_day_of_week').removeClass('d-none');
        }
    });

    $('body').on('change', '#checked_address', function (e) {

        if ($('#checked_address').prop('checked')) {
            $('.trading-address').find('.trcountry, .trcounty, .traddress, .trcity, .trpostal_code').prop('disabled', true);
        } else {
            $('.trading-address').find('.trcountry, .trcounty, .traddress, .trcity, .trpostal_code').prop('disabled', false);
        }
    });

    $('body').on('click', '.row-url-redirect td', function (e) {

        if (!$(e.currentTarget).hasClass('no-rowurl-redirect') && $(this).parent().data('rowurl')) {
            window.location.href = $(this).parent().data('rowurl');
        }
    });

    $('body').on('change', '.categories-conditions-type', function (e) {

        if ($(this).val() == 'manual') {
            $(".automated-conditions-type").addClass('d-none');
        } else {
            $(".automated-conditions-type").removeClass('d-none');
        }
    });

    $('body').on('click', '.dz-remove-btn', function (e) {

        var _this = $(this);
        var url = $(this).data('url');
        var id = $(this).data('id');
        var type = $(this).data('type');

        if (url && id) {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    id: id,
                    type: type
                },
                success: function (data) {}
            });

            _this.parents('.dz-image-preview').remove();
        }
    });

    $('body').on('change', '.show-section', function (e) {

        if ($(this).data('changetype')) {
            if ($(".section-" + $(this).data('changetype')).length) {
                $(".section-" + $(this).data('changetype')).addClass('d-none');
            }

            if ($(this).hasClass('no-check')) {
                if ($(".section-" + $(this).data('changetype') + "-" + $(this).val()).length) {
                    $(".section-" + $(this).data('changetype') + "-" + $(this).val()).removeClass('d-none');
                }
            } else {
                if (this.checked) {
                    if ($(".section-" + $(this).data('changetype') + "-" + $(this).val()).length) {
                        $(".section-" + $(this).data('changetype') + "-" + $(this).val()).removeClass('d-none');
                    }
                }
            }
        }
    });

    $('body').on('click', '.generate-random-code', function (e) {

        if ($(".show-random-code").length) {
            $(".show-random-code").val(makeCode(8, 1));
        }
    });

    $('body').on('click', '.show-conditional-pricing', function (e) {

        if ($(".conditional-pricing-for-rates").hasClass('d-none')) {
            $(".conditional-pricing-for-rates").removeClass('d-none');
            $(this).text('Remove conditional pricing');
            $("#conditional_pricing").val(1);
        } else {
            $(".conditional-pricing-for-rates").addClass('d-none');
            $(this).text('Add conditional pricing');
            $("#conditional_pricing").val('');
        }
    });

    $('body').on('change', '#store_currency', function (e) {

        if ($("#store_currency_format").length) {
            $("#store_currency_format").val($(this).find(':selected').data('symbol') + '[AMOUNT] ' + $(this).find(':selected').data('code'));
        }
    });

    $('body').on('change', '.search-for-states', function (e) {

        if ($(this).val()) {
            var _this = $(this);
            var url = $('meta[name="get-country-states"]').attr('content');

            if (url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        country_id: $(this).val()
                    },
                    success: function (data) {

                        if (data.status == 1) {
                            if (data.states.length) {
                                var options = '';

                                $.each(data.states, function (key, val) {

                                    options += '<option value="' + val.reference_id + '">' + val.name + '</option>'
                                });

                                if (_this.parents('.node').find(".country-states-input").length) {
                                    _this.parents('.node').find(".country-states-input").addClass('d-none');
                                }

                                if (_this.parents('.node').find(".country-states-select").length) {
                                    _this.parents('.node').find(".country-states-select").removeClass('d-none');
                                }

                                if (_this.parents('.node').find(".add-states-list").length) {
                                    _this.parents('.node').find(".add-states-list").html(options);

                                    if (_this.parents('.node').find(".add-states-list").hasClass('select2')) {
                                        _this.parents('.node').find(".add-states-list").select2({
                                            width: "100%",
                                            placeholder: _this.parents('.node').find(".add-states-list").data('placeholder'),
                                        });
                                    }
                                }

                            }
                        }
                    }
                });
            }
        }
    });

    $('body').on('change', '.search-for-states-for-taxes', function (e) {

        if ($(this).val()) {
            var _this = $(this);
            var url = $('meta[name="get-country-states"]').attr('content');

            if (url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        country_id: $(this).val()
                    },
                    success: function (data) {

                        if (data.status == 1 && data.states.length && _this.parents('.node').find(".show-states-list").length) {
                            var states_option = '<select name="state[]" class="form-select"><option value="">For all states</option>';

                            $.each(data.states, function (key, val) {

                                states_option += '<option value="' + val.reference_id + '">' + val.name + '</option>';
                            });

                            states_option += '</select>';

                            _this.parents('.node').find(".show-states-list").html(states_option);
                        } else {
                            _this.parents('.node').find(".show-states-list").html(`<input type="text" name="state[]" class="form-control" placeholder="*">`);
                        }
                    }
                });
            }
        }
    });

    $('body').on('click', '.fetch-content', function (e) {

        if ($(this).data('type')) {
            var _this = $(this);
            var type = $(this).data('type');
            var url = $('meta[name="policy-template-url"]').attr('content');

            if (url) {
                buttonLoading(_this);

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        type: type
                    },
                    success: function (data) {

                        if (data && $("." + type).length) {
                            $("." + type).summernote("code", data);
                        }

                        buttonLoading(_this, 0);
                    }
                });
            }
        }
    });

    $("body").on('change', '.business-unit', function (e) {

        if ($(this).val() == 'BCA') {
            $("#debtor_id_2").addClass('item-disabled');
            $("#debtor_id_2").prop('disabled', true);

            $("#debtor_id").removeClass('item-disabled');
            $("#debtor_id").prop('disabled', false);
        } else if ($(this).val() == 'OPS') {
            $("#debtor_id_2").addClass('item-disabled');
            $("#debtor_id").addClass('item-disabled');
            $("#debtor_id_2").prop('disabled', true);
            $("#debtor_id").prop('disabled', true);
        } else {
            $("#debtor_id_2").removeClass('item-disabled');
            $("#debtor_id_2").prop('disabled', false);

            $("#debtor_id").removeClass('item-disabled');
            $("#debtor_id").prop('disabled', false);
        }
    });

    $("body").on('change keyup paste', '.update-value-cais', function (e) {

        clearTimeout(typingTimer);

        var _this = $(this);

        typingTimer = setTimeout(function () {
            updateCAISData(_this.parents('.cais-tr').data('id'), _this.data('name'), _this.val());
        }, doneTypingInterval);
    });

    $("body").on('change', '.update-value-cais-checkbox', function (e) {

        if (this.checked) {
            updateCAISData($(this).val(), $(this).data('name'), 1);
        } else {
            updateCAISData($(this).val(), $(this).data('name'), 0);
        }
    });

    $('body').on('change', '.cais-action', function (e) {

        var url = $('meta[name="cais-action-url"]').attr('content');

        var _this = $(this);
        var action = $(this).val();

        if (action && url) {
            var title = 'Are you sure?';
            var text = 'You will not be able to revert this!';
            var icon = 'warning';
            var confirm_button_color = '#3085d6';
            var cancel_button_color = '#d33';
            var confirm_button_text = 'Yes, Remove It!';

            if (_this.find(':selected').data('title')) {
                title = _this.find(':selected').data('title');
            }

            if (_this.find(':selected').data('text')) {
                text = _this.find(':selected').data('text');
            }

            if (_this.find(':selected').data('icon')) {
                icon = _this.find(':selected').data('icon');
            }

            if (_this.find(':selected').data('confirmbuttoncolor')) {
                confirm_button_color = _this.find(':selected').data('confirmbuttoncolor');
            }

            if (_this.find(':selected').data('cancelbuttoncolor')) {
                cancel_button_color = _this.find(':selected').data('cancelbuttoncolor');
            }

            if (_this.find(':selected').data('confirmbuttontext')) {
                confirm_button_text = _this.find(':selected').data('confirmbuttontext');
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: confirm_button_color,
                cancelButtonColor: cancel_button_color,
                confirmButtonText: confirm_button_text,
                preConfirm: function (t) {

                    return fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: 'action=' + action,
                        }).then(function (t) {
                            if (!t.ok) throw new Error(t.statusText);

                            return t.json();
                        })
                        .then(function (data) {

                            if (data.swal_message) {
                                if (data.swal_title) {
                                    var swal_title = data.swal_title;
                                } else {
                                    var swal_title = 'Success';
                                }

                                Swal.fire({
                                    title: swal_title,
                                    text: data.swal_message,
                                    icon: "success",
                                });
                            }
                        })
                        .catch(function (t) {
                            Swal.showValidationMessage("Request failed. Please refresh the page and try again")
                        });
                }
            });
        }
    });
});

function buttonLoading(button, start = 1, prop = 1) {
    if (start == 1) {
        button.find('.processing-show').removeClass('d-none');
        button.find('.default-show').addClass('d-none');
        button.addClass('disabled');

        if (prop == 1) {
            button.prop('disabled', true);
        }
    } else {
        button.find('.processing-show').addClass('d-none');
        button.find('.default-show').removeClass('d-none');
        button.removeClass('disabled');

        if (prop == 1) {
            button.prop('disabled', false);
        }
    }
}

function clearValidationErrors(_this = null) {
    if (_this) {
        _this.find('.is-invalid').removeClass('is-invalid');
        _this.find('.invalid-feedback').remove();
    } else {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }
}

document.onkeypress = function (e) {
    resetMessages();
}

function resetMessages() {
    if ($(".display-messages").length) {
        $(".display-messages").slideUp('slow');
    }

    clearValidationErrors();
}

function updateProgress(oEvent) {
    if (oEvent.lengthComputable && $(".file-uploader").length) {
        var percentComplete = oEvent.loaded / oEvent.total;
        percentComplete = parseInt(percentComplete * 100);
        $(".file-uploader").removeClass('d-none');
        $(".file-uploader .progress-bar").attr("aria-valuenow", percentComplete);
        $(".file-uploader .progress-bar").css("width", percentComplete + "%");
        $(".file-uploader .progress-bar").text(percentComplete + "%");
    }
}

function refreshTable() {

    try {
        if (table) {
            if ($(".remove-all-button").length) {
                $(".remove-all-button").addClass('d-none');
            }

            if ($(".select-all-checkbox").length) {
                $(".select-all-checkbox").prop('checked', false);
            }

            table.ajax.reload();

            if ($('#filter-modal').length) {
                $('#filter-modal').modal('hide');
                $('.filter-button').show();
                $('.filter-button').next('.processing-button').hide();
            }
        }
    } catch (err) {}
}

// by suraj
function refreshSOPTable() {

    if ($(".select-all-checkbox-for-sop").length) {
        $(".select-all-checkbox-for-sop").prop('checked', false);
    }

    if ($(".select-sop-item-checkbox").length) {
        $(".select-sop-item-checkbox").prop('checked', false);
    }

    if ($('#sopModel').length) {
        $('#sopModel').modal('hide');
        $(".send-sop").addClass('d-none');
    }

}

function removeItemData(_this, id, url, redirect = '') {
    var title = 'Are you sure?';
    var text = 'You will not be able to revert this!';
    var icon = 'warning';
    var confirm_button_color = '#3085d6';
    var cancel_button_color = '#d33';
    var confirm_button_text = 'Yes, Remove It!';

    if (_this.data('title')) {
        title = _this.data('title');
    }

    if (_this.data('text')) {
        text = _this.data('text');
    }

    if (_this.data('icon')) {
        icon = _this.data('icon');
    }

    if (_this.data('confirmbuttoncolor')) {
        confirm_button_color = _this.data('confirmbuttoncolor');
    }

    if (_this.data('cancelbuttoncolor')) {
        cancel_button_color = _this.data('cancelbuttoncolor');
    }

    if (_this.data('confirmbuttontext')) {
        confirm_button_text = _this.data('confirmbuttontext');
    }


    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: confirm_button_color,
        cancelButtonColor: cancel_button_color,
        confirmButtonText: confirm_button_text,
        preConfirm: function (t) {

            return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: 'id=' + id,
                }).then(function (t) {
                    if (!t.ok) throw new Error(t.statusText);

                    if (redirect || !table) {
                        location.reload();
                    } else {
                        refreshTable();
                    }
                    return t.json();
                })
                .catch(function (t) {
                    Swal.showValidationMessage("Request failed. Please refresh the page and try again")
                });
        }
    });
}

function openNavigation() {
    var class_name = $('meta[name="class-to-open"]').attr('content');

    if (class_name) {
        var _this = $('.side-nav .' + class_name);

        if (_this.length) {
            _this.addClass("menuitem-active");
            _this.find('a').addClass("active");

            if (_this.parent().parent().hasClass('collapse')) {
                _this.parent().parent().addClass('show');
                _this.parent().parent().prev().attr('aria-expanded', 'true')
            }
        }
    }
}

function makeCode(length, upper_case = '') {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;

    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }

    if (upper_case) {
        result = result.toUpperCase();
    }

    return result;
}

function updateCAISData(id, field, value = '') {
    var url = $('meta[name="cis-value-update-url"]').attr('content');

    if (url) {
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                field: field,
                value: value
            },
            success: function (data) {}
        });
    }
}
