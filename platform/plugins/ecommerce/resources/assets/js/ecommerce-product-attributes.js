class EcommerceProductAttribute {
    constructor() {
        this.template = $('#product_attribute_template').html();
        this.totalItem = 0;

        this.deletedItems = [];

        this.handleChooseImage();
        this.handleForm();
        this.initSpectrum();
    }

    handleChooseImage() {
        new RvMediaStandAlone('.image-box .btn-images', {
            filter: 'image',
            view_in: 'all_media',
            onSelectFiles: (files, $el) => {
                let firstItem = _.first(files);
                if (firstItem) {
                    $el.closest('.image-box').find('.image-data').val(firstItem.url);
                    $el.closest('.image-box').find('.preview_image').attr('src', firstItem.thumb).show();
                }
            }
        });
    }

    initSpectrum() {
        $('.input-color-picker').each((index, item) => {
            let $current = $(item);

            $current.spectrum({
                allowEmpty: true,
                color: $current.val() || 'rgb(51, 51, 51)',
                showInput: true,
                containerClassName: 'full-spectrum',
                showInitial: true,
                showSelectionPalette: false,
                showPalette: true,
                showAlpha: true,
                preferredFormat: 'hex',
                showButtons: false,
                palette: [
                    [
                        "rgb(0, 0, 0)", "rgb(102, 102, 102)", "rgb(183, 183, 183)",
                        "rgb(217, 217, 217)", "rgb(239, 239, 239)", "rgb(243, 243, 243)", "rgb(255, 255, 255)",
                        "rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                        "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                        "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                        "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                        "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                        "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                        "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                        "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                        "rgb(133, 32, 12)", "rgb(153, 0, 0)", "rgb(180, 95, 6)", "rgb(191, 144, 0)", "rgb(56, 118, 29)",
                        "rgb(19, 79, 92)", "rgb(17, 85, 204)", "rgb(11, 83, 148)", "rgb(53, 28, 117)", "rgb(116, 27, 71)",
                        "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                        "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)",
                        "rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
                        "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"
                    ],
                ],
                change: (color) => {
                    $current.val(color.toRgbString());
                },
            });
        });
    }

    addNewAttribute() {
        let _self = this;

        let template = _self.template
            .replace(/__id__/gi, 0)
            .replace(/__position__/gi, 0)
            .replace(/__checked__/gi, (_self.totalItem == 0 ? 'checked' : ''))
            .replace(/__title__/gi, '')
            .replace(/__slug__/gi, '')
            .replace(/__color__/gi, '')
            .replace(/__image__/gi, '');

        $('.swatches-container .swatches-list').append(template);

        _self.totalItem++;
    }

    exportData() {
        let data = [];

        $('.swatches-container .swatches-list li').each((index, item) => {
            let $current = $(item);
            data.push({
                id: $current.data('id'),
                is_default: ($current.find('input[type=radio]').is(':checked') ? 1 : 0),
                order: $current.index(),
                title: $current.find('.swatch-title input').val(),
                slug: $current.find('.swatch-slug input').val(),
                color: $current.find('.swatch-value input').val(),
                image: $current.find('.swatch-image input').val(),
            });
        });

        return data;
    }

    handleForm() {
        let _self = this;

        $('.swatches-container .swatches-list').sortable();

        $('body')
            .on('submit', '.update-attribute-set-form', () => {
                let data = _self.exportData();

                $('#attributes').val(JSON.stringify(data));

                $('#deleted_attributes').val(JSON.stringify(_self.deletedItems));
            })
            .on('click', '.js-add-new-attribute', event => {
                event.preventDefault();

                _self.addNewAttribute();

                _self.initSpectrum();
            })
            .on('click', '.swatches-container .swatches-list li .remove-item a', event => {
                event.preventDefault();

                let $item = $(event.currentTarget).closest('li');

                _self.deletedItems.push($item.data('id'));

                $item.remove();
            });
    }
}

$(window).on('load', () => {
    new EcommerceProductAttribute();
});
