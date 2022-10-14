$(document).ready(() => {
    if ($.fn.datepicker) {
        $('#date_of_birth').datepicker({
            format: 'yyyy-mm-dd',
            orientation: 'bottom'
        });
    }

    $('#avatar').on('change', event => {
        let input = event.currentTarget;
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => {
                $('.userpic-avatar')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

    $(document).on('click', '.btn-trigger-delete-address', function (event) {
        event.preventDefault();
        $('.btn-confirm-delete').data('url', $(this).data('url'));
        $('#confirm-delete-modal').modal('show');
    });

    $(document).on('click', '.btn-confirm-delete', function (event) {
        event.preventDefault();

        let $current = $(this);

        $current.addClass('button-loading');

        $.ajax({
            url: $current.data('url'),
            type: 'GET',
            success: data => {
                $current.closest('.modal').modal('hide');
                $current.removeClass('button-loading');

                if (data.error) {
                    window.showAlert('alert-danger', data.message);
                } else {
                    window.showAlert('alert-success', data.message);

                    $('.btn-trigger-delete-address[data-url="' + $current.data('url') + '"]').closest('.dashboard-address-item').remove();
                }
            },
            error: data => {
                handleError(data);
                $current.removeClass('button-loading');
            }
        });
    });
});

