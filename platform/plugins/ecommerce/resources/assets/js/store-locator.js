class StoreLocatorManagement {
    init() {
        $(document).on('click', '.btn-trigger-show-store-locator', event => {
            event.preventDefault();
            let $current = $(event.currentTarget);
            let $modalBody;
            if ($current.data('type') === 'update') {
                $modalBody = $('#update-store-locator-modal .modal-body');
            } else {
                $modalBody = $('#add-store-locator-modal .modal-body');
            }

            $.ajax({
                url: $current.data('load-form'),
                type: 'GET',
                beforeSend: () => {
                    $current.addClass('button-loading');
                    $modalBody.html('');
                },
                success: res => {
                    if (res.error) {
                        Botble.showError(res.message);
                    } else {
                        $modalBody.html(res.data);
                        Botble.initResources();
                        $modalBody.closest('.modal.fade').modal('show');
                    }
                    $current.removeClass('button-loading');
                },
                complete: () => {
                    $current.removeClass('button-loading');
                },
                error: data => {
                    $current.removeClass('button-loading');
                    Botble.handleError(data);
                },
            });
        });

        let createOrUpdateStoreLocator = _self => {

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('.modal-content').find('form').prop('action'),
                data: _self.closest('.modal-content').find('form').serialize(),
                success: res => {
                    if (!res.error) {
                        Botble.showSuccess(res.message);
                        $('.store-locator-wrap').load(window.location.href + ' .store-locator-wrap > *');
                        _self.removeClass('button-loading');
                        _self.closest('.modal.fade').modal('hide');
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
        };

        $(document).on('click', '#add-store-locator-button', event => {
            event.preventDefault();
            createOrUpdateStoreLocator($(event.currentTarget));
        });

        $(document).on('click', '#update-store-locator-button', event => {
            event.preventDefault();
            createOrUpdateStoreLocator($(event.currentTarget));
        });

        $(document).on('click', '.btn-trigger-delete-store-locator', event => {
            event.preventDefault();
            $('#delete-store-locator-button').data('target', $(event.currentTarget).data('target'));
            $('#delete-store-locator-modal').modal('show');
        });

        $(document).on('click', '#delete-store-locator-button', event => {
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
                        $('.store-locator-wrap').load(window.location.href + ' .store-locator-wrap > *');
                        _self.removeClass('button-loading');
                        _self.closest('.modal.fade').modal('hide');
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

        $(document).on('click', '#change-primary-store-locator-button', event => {
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
                        $('.store-locator-wrap').load(window.location.href + ' .store-locator-wrap > *');
                        _self.removeClass('button-loading');
                        _self.closest('.modal.fade').modal('hide');
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
    new StoreLocatorManagement().init();
});
