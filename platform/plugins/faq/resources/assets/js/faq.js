'use strict';

$(document).ready(function () {
    $(document).on('click', '.add-faq-schema-items', function (event) {
        event.preventDefault();

        $('.faq-schema-items').toggleClass('hidden');
    });
});
