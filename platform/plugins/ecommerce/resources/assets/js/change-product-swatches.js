class ChangeProductSwatches {
    constructor() {
        this.xhr = null;

        this.handleEvents();
    }

    handleEvents() {
        let _self = this;
        let $body = $('body');

        $body.on('click', '.product-attributes .visual-swatch label, .product-attributes .text-swatch label', event => {
            event.preventDefault();

            let $radio = $(event.currentTarget).find('input[type=radio]');

            if ($radio.is(':checked')) {
                return false;
            }

            $radio.prop('checked', true);

            if ($(event.currentTarget).closest('.visual-swatch').find('input[type=radio]:checked').length < 1) {
                $radio.prop('checked', true);
            }

            $radio.trigger('change');
        });

        $body.off('change').on('change', '.product-attributes input, .product-attributes select', event => {
            let selectedAttributeSets = 0;
            $(event.currentTarget).closest('.product-attributes').find('.attribute-swatches-wrapper').each((index, el) => {
                let $current = $(el);

                let attribute;

                if ($current.data('type') === 'dropdown') {
                    attribute = $current.find('select').val();
                } else {
                    attribute = $current.find('input[type=radio]:checked').val();
                }

                if (attribute) {
                    selectedAttributeSets++;
                }
            });
            _self.getProductVariation($(event.currentTarget).closest('.product-attributes'));
        });
    }

    getProductVariation($productAttributes) {
        let _self = this;

        let attributes = [];

        /**
         * Break current request
         */
        if (_self.xhr) {
            _self.xhr.abort();

            _self.xhr = null;
        }

        /**
         * Get attributes
         */
        $productAttributes.find('.attribute-swatches-wrapper').each((index, el) => {
            let $current = $(el);

            let attribute;

            if ($current.data('type') === 'dropdown') {
                attribute = $current.find('select').val();
            } else {
                attribute = $current.find('input[type=radio]:checked').val();
            }

            if (attribute) {
                attributes.push(attribute);
            }
        });

        if (attributes.length) {
            _self.xhr = $.ajax({
                url: $productAttributes.data('target'),
                type: 'GET',
                data: {
                    attributes: attributes
                },
                beforeSend: () => {
                    if (window.onBeforeChangeSwatches && typeof window.onBeforeChangeSwatches === 'function') {
                        window.onBeforeChangeSwatches(attributes, $productAttributes);
                    }
                },
                success: data => {
                    if (window.onChangeSwatchesSuccess && typeof window.onChangeSwatchesSuccess === 'function') {
                        window.onChangeSwatchesSuccess(data, $productAttributes);
                    }
                },
                complete: data => {
                    if (window.onChangeSwatchesComplete && typeof window.onChangeSwatchesComplete === 'function') {
                        window.onChangeSwatchesComplete(data, $productAttributes);
                    }
                },
                error: data => {
                    if (window.onChangeSwatchesError && typeof window.onChangeSwatchesError === 'function') {
                        window.onChangeSwatchesError(data, $productAttributes);
                    }
                },
            });
        } else {

            if (window.onBeforeChangeSwatches && typeof window.onBeforeChangeSwatches === 'function') {
                window.onBeforeChangeSwatches({attributes}, $productAttributes);
            }

            if (window.onChangeSwatchesSuccess && typeof window.onChangeSwatchesSuccess === 'function') {
                window.onChangeSwatchesSuccess(null, $productAttributes);
            }

            if (window.onChangeSwatchesComplete && typeof window.onChangeSwatchesComplete === 'function') {
                window.onChangeSwatchesComplete(null, $productAttributes);
            }

            if (window.onChangeSwatchesError && typeof window.onChangeSwatchesError === 'function') {
                window.onChangeSwatchesError(null, $productAttributes);
            }
        }
    }
}

$(document).ready(() => {
    new ChangeProductSwatches();
});
