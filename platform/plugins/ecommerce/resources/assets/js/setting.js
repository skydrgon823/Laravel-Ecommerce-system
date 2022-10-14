$(document).ready(() => {
    $(document).on('keyup', '#store_order_prefix', event => {
        if ($(event.currentTarget).val()) {
            $('.sample-order-code-prefix').text($(event.currentTarget).val() + '-');
        } else {
            $('.sample-order-code-prefix').text('');
        }
    });

    $(document).on('keyup', '#store_order_suffix', event => {
        if ($(event.currentTarget).val()) {
            $('.sample-order-code-suffix').text('-' + $(event.currentTarget).val());
        } else {
            $('.sample-order-code-suffix').text('');
        }
    });

    $(document).on('change', '.check-all', event => {
        let _self = $(event.currentTarget);
        let set = _self.attr('data-set');
        let checked = _self.prop('checked');
        $(set).each((index, el) => {
            if (checked) {
                $(el).prop('checked', true);
            } else {
                $(el).prop('checked', false);
            }
        });
    });

    $('.trigger-input-option').on('change', function() {
        let $settingContentContainer = $($(this).data('setting-container'));
        if ($(this).val() == '1') {
            $settingContentContainer.removeClass('d-none');
        } else {
            $settingContentContainer.addClass('d-none');
        }
    });
});
