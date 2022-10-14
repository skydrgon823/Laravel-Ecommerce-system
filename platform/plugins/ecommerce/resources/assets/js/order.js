class OrderAdminManagement {
    init() {
        $(document).on('click', '.btn-confirm-order', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: _self.closest('form').serialize(),
                success: res => {
                    if (!res.error) {
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        _self.closest('div').remove();
                        Botble.showSuccess(res.message);
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-trigger-resend-order-confirmation-modal', event => {
            event.preventDefault();
            $('#confirm-resend-confirmation-email-button').data('action', $(event.currentTarget).data('action'));
            $('#resend-order-confirmation-email-modal').modal('show');
        });

        $(document).on('click', '#confirm-resend-confirmation-email-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('action'),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                    $('#resend-order-confirmation-email-modal').modal('hide');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-trigger-shipment', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            let $formBody = $('.shipment-create-wrap');
            $formBody.toggleClass('hidden');
            if (!$formBody.hasClass('shipment-data-loaded')) {

                Botble.blockUI({
                    target: $formBody,
                    iconOnly: true,
                    overlayColor: 'none'
                });

                $.ajax({
                    url: _self.data('target'),
                    type: 'GET',
                    success: res => {
                        if (res.error) {
                            Botble.showError(res.message);
                        } else {
                            $formBody.html(res.data);
                            $formBody.addClass('shipment-data-loaded');
                            Botble.initResources();
                        }
                        Botble.unblockUI($formBody);
                    },
                    error: data => {
                        Botble.handleError(data);
                        Botble.unblockUI($formBody);
                    },
                });
            }
        });

        $(document).on('change', '#store_id', event => {
            let $formBody = $('.shipment-create-wrap');
            Botble.blockUI({
                target: $formBody,
                iconOnly: true,
                overlayColor: 'none'
            });

            $('#select-shipping-provider').load($('.btn-trigger-shipment').data('target') + '?view=true&store_id=' + $(event.currentTarget).val() + ' #select-shipping-provider > *', () => {
                Botble.unblockUI($formBody);
                Botble.initResources();
            });
        });

        $(document).on('change', '.shipment-form-weight', event => {
            let $formBody = $('.shipment-create-wrap');
            Botble.blockUI({
                target: $formBody,
                iconOnly: true,
                overlayColor: 'none'
            });

            $('#select-shipping-provider').load($('.btn-trigger-shipment').data('target') + '?view=true&store_id=' + $('#store_id').val() + '&weight=' + $(event.currentTarget).val() + ' #select-shipping-provider > *', () => {
                Botble.unblockUI($formBody);
                Botble.initResources();
            });
        });

        $(document).on('click', '.table-shipping-select-options .clickable-row', event => {
            let _self = $(event.currentTarget);
            $('.input-hidden-shipping-method').val(_self.data('key'));
            $('.input-hidden-shipping-option').val(_self.data('option'));
            $('.input-show-shipping-method').val(_self.find('span.ws-nm').text());
        });

        $(document).on('click', '.btn-create-shipment', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: _self.closest('form').serialize(),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        $('.btn-trigger-shipment').remove();
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-cancel-shipment', event => {
            event.preventDefault();
            $('#confirm-cancel-shipment-button').data('action', $(event.currentTarget).data('action'));
            $('#cancel-shipment-modal').modal('show');
        });

        $(document).on('click', '#confirm-cancel-shipment-button', event => {
            event.preventDefault();

            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('action'),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                        $('.carrier-status').addClass('carrier-status-' + res.data.status).text(res.data.status_text);
                        $('#cancel-shipment-modal').modal('hide');
                        $('#order-history-wrapper').load(window.location.href + ' #order-history-wrapper > *');
                        $('.shipment-actions-wrapper').remove();
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-close-shipment-panel', event => {
            event.preventDefault();
            $('.shipment-create-wrap').addClass('hidden');
        });

        $(document).on('click', '.btn-trigger-update-shipping-address', event => {
            event.preventDefault();
            $('#update-shipping-address-modal').modal('show');
        });

        $(document).on('click', '#confirm-update-shipping-address-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('.modal-content').find('form').prop('action'),
                data: _self.closest('.modal-content').find('form').serialize(),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                        $('#update-shipping-address-modal').modal('hide');
                        $('.shipment-address-box-1').html(res.data.line);
                        $('.text-infor-subdued.shipping-address-info').html(res.data.detail);
                        let $formBody = $('.shipment-create-wrap');
                        Botble.blockUI({
                            target: $formBody,
                            iconOnly: true,
                            overlayColor: 'none'
                        });

                        $('#select-shipping-provider').load($('.btn-trigger-shipment').data('target') + '?view=true #select-shipping-provider > *', () => {
                            Botble.unblockUI($formBody);
                            Botble.initResources();
                        });
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-update-order', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: _self.closest('form').serialize(),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-trigger-cancel-order', event => {
            event.preventDefault();
            $('#confirm-cancel-order-button').data('target', $(event.currentTarget).data('target'));
            $('#cancel-order-modal').modal('show');
        });

        $(document).on('click', '#confirm-cancel-order-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('target'),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        $('#cancel-order-modal').modal('hide');
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-trigger-confirm-payment', event => {
            event.preventDefault();
            $('#confirm-payment-order-button').data('target', $(event.currentTarget).data('target'));
            $('#confirm-payment-modal').modal('show');
        });

        $(document).on('click', '#confirm-payment-order-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('target'),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        $('#confirm-payment-modal').modal('hide');
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.show-timeline-dropdown', event => {
            event.preventDefault();
            $($(event.currentTarget).data('target')).slideToggle();
            $(event.currentTarget).closest('.comment-log-item').toggleClass('bg-white');

        });

        $(document).on('keyup', '.input-sync-item', event => {
            let number = $(event.currentTarget).val();
            if (!number || isNaN(number)) {
                number = 0;
            }
            $(event.currentTarget).closest('.page-content').find($(event.currentTarget).data('target')).text(Botble.numberFormat(parseFloat(number), 2));
        });

        $(document).on('click', '.btn-trigger-refund', event => {
            event.preventDefault();
            $('#confirm-refund-modal').modal('show');
        });

        $(document).on('change', '.j-refund-quantity', () => {
            let total_restock_items = 0;
            $.each($('.j-refund-quantity'), (index, el) => {
                let number = $(el).val();
                if (!number || isNaN(number)) {
                    number = 0;
                }
                total_restock_items += parseFloat(number);
            });

            $('.total-restock-items').text(total_restock_items);
        });

        $(document).on('click', '#confirm-refund-payment-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('.modal-dialog').find('form').prop('action'),
                data: _self.closest('.modal-dialog').find('form').serialize(),
                success: res => {
                    if (!res.error) {
                        if (res.data && res.data.refund_redirect_url) {
                            window.location.href = res.data.refund_redirect_url;
                        } else {
                            $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                            Botble.showSuccess(res.message);
                            _self.closest('.modal').modal('hide');
                        }
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-trigger-update-shipping-status', event => {
            event.preventDefault();
            $('#update-shipping-status-modal').modal('show');
        });

        $(document).on('click', '#confirm-update-shipping-status-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('.modal-dialog').find('form').prop('action'),
                data: _self.closest('.modal-dialog').find('form').serialize(),
                success: res => {
                    if (!res.error) {
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        Botble.showSuccess(res.message);
                        _self.closest('.modal').modal('hide');
                    } else {
                        Botble.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Botble.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });
    }
}

$(document).ready(() => {
    new OrderAdminManagement().init();
});
