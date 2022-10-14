class ShipmentManagement {
    init() {
        $(document).on('click', '.shipment-actions .dropdown-menu a', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            $('#confirm-change-shipment-status-button').data('target', _self.data('target')).data('status', _self.data('value'));
            let $modal = $('#confirm-change-status-modal');
            $modal.find('.shipment-status-label').text(_self.text().toLowerCase());
            $modal.modal('show');
        });

        $(document).on('click', '#confirm-change-shipment-status-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('target'),
                data: {
                    status: _self.data('status')
                },
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                        $('.max-width-1200').load(window.location.href + ' .max-width-1200 > *', () => {
                            $('#confirm-change-status-modal').modal('hide');
                            _self.removeClass('button-loading');
                        });
                    } else {
                        Botble.showError(res.message);
                        _self.removeClass('button-loading');
                    }
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
    new ShipmentManagement().init();
});
