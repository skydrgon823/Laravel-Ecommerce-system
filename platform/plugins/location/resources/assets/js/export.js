$(() => {

    let isExporting = false;

    $(document).on('click', '.btn-export-data', function (event) {
        event.preventDefault();

        if (isExporting) {
            return;
        }

        const $this = $(event.currentTarget);
        const $content = $this.html();

        $.ajax({
            url: $this.attr('href'),
            method: 'POST',
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: () => {
                $this.html($this.data('loading-text'));
                $this.attr('disabled', 'true');
                isExporting = true;
            },
            success: data => {
                let a = document.createElement('a');
                let url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = $this.data('filename');
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            },
            error: data => {
                Botble.handleError(data);
            },
            complete: () => {
                setTimeout(() => {
                    $this.html($content);
                    $this.removeAttr('disabled');
                    isExporting = false;
                }, 2000);
            }
        });
    });
});
