'use strict';

class PaymentMethodManagement {
    init() {
        $('.toggle-payment-item').off('click').on('click', event => {
            $(event.currentTarget).closest('tbody').find('.payment-content-item').toggleClass('hidden');
        });
        $('.disable-payment-item').off('click').on('click', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            $('#confirm-disable-payment-method-modal').modal('show');
            $('#confirm-disable-payment-method-button').on('click', event => {
                event.preventDefault();
                $(event.currentTarget).addClass('button-loading');
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: $('div[data-disable-payment-url]').data('disable-payment-url'),
                    data: {
                        type: _self.closest('form').find('.payment_type').val()
                    },
                    success: res => {
                        if (!res.error) {
                            _self.closest('tbody').find('.payment-name-label-group').addClass('hidden');
                            _self.closest('tbody').find('.edit-payment-item-btn-trigger').addClass('hidden');
                            _self.closest('tbody').find('.save-payment-item-btn-trigger').removeClass('hidden');
                            _self.closest('tbody').find('.btn-text-trigger-update').addClass('hidden');
                            _self.closest('tbody').find('.btn-text-trigger-save').removeClass('hidden');
                            _self.addClass('hidden');
                            $('#confirm-disable-payment-method-modal').modal('hide');
                            Botble.showSuccess(res.message);
                        } else {
                            Botble.showError(res.message);
                        }
                        $('#confirm-disable-payment-method-button').removeClass('button-loading');
                    },
                    error: res => {
                        Botble.handleError(res);
                        $('#confirm-disable-payment-method-button').removeClass('button-loading');
                    }
                });
            });
        });

        $('.save-payment-item').off('click').on('click', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');
            $.ajax({
                type: 'POST',
                cache: false,
                url: $('div[data-update-payment-url]').data('update-payment-url'),
                data: _self.closest('form').serialize(),
                success: res => {
                    if (!res.error) {
                        _self.closest('tbody').find('.payment-name-label-group').removeClass('hidden');
                        _self.closest('tbody').find('.method-name-label').text(_self.closest('form').find('input[name=name]').val());
                        _self.closest('tbody').find('.disable-payment-item').removeClass('hidden');
                        _self.closest('tbody').find('.edit-payment-item-btn-trigger').removeClass('hidden');
                        _self.closest('tbody').find('.save-payment-item-btn-trigger').addClass('hidden');
                        _self.closest('tbody').find('.btn-text-trigger-update').removeClass('hidden');
                        _self.closest('tbody').find('.btn-text-trigger-save').addClass('hidden');
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
    }
}

$(document).ready(() => {
    new PaymentMethodManagement().init();
});
