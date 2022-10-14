<?php

app()->booted(function () {
    theme_option()
        ->setField([
            'id'         => 'logo_light',
            'section_id' => 'opt-text-subsection-logo',
            'type'       => 'mediaImage',
            'label'      => __('Light Logo'),
            'attributes' => [
                'name'  => 'logo_light',
                'value' => null,
            ],
        ])
        ->setField([
            'id'         => 'preloader_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'customSelect',
            'label'      => __('Enable Preloader?'),
            'attributes' => [
                'name'    => 'preloader_enabled',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'preloader_version',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'customSelect',
            'label'      => __('Preloader Version?'),
            'attributes' => [
                'name'    => 'preloader_version',
                'list'    => [
                    'v1' => 'V1',
                    'v2' => 'V2',
                ],
                'value'   => 'v1',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'preloader_image',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'mediaImage',
            'label'      => __('Preloader image (if it is not set, preloader image will be site favicon)'),
            'attributes' => [
                'name'  => 'preloader_image',
                'value' => null,
            ],
        ])
        ->setField([
            'id'         => 'enabled_browse_categories_on_header',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'customSelect',
            'label'      => __('Enable Browse Categories button on header?'),
            'attributes' => [
                'name'    => 'enabled_browse_categories_on_header',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'copyright',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Copyright'),
            'attributes' => [
                'name'    => 'copyright',
                'value'   => 'Copyright © 2021 Wowy all rights reserved. Powered by Botble.',
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => __('Change copyright'),
                    'data-counter' => 250,
                ],
            ],
            'helper'     => __('Copyright on footer of site'),
        ])
        ->setField([
            'id'         => 'hotline',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Hotline'),
            'attributes' => [
                'name'    => 'hotline',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => __('Hotline'),
                    'data-counter' => 30,
                ],
            ],
        ])
        ->setField([
            'id'         => 'phone',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Phone'),
            'attributes' => [
                'name'    => 'phone',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => __('Phone'),
                    'data-counter' => 30,
                ],
            ],
        ])
        ->setField([
            'id'         => 'address',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Address'),
            'attributes' => [
                'name'    => 'address',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => __('Address'),
                    'data-counter' => 120,
                ],
            ],
        ])
        ->setField([
            'id'         => 'working_hours',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Working Hours'),
            'attributes' => [
                'name'    => 'working_hours',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => __('Working Hours'),
                    'data-counter' => 120,
                ],
            ],
        ])
        ->setField([
            'id'         => 'payment_methods',
            'section_id' => 'opt-text-subsection-ecommerce',
            'type'       => 'mediaImage',
            'label'      => __('Accepted Payment methods'),
            'attributes' => [
                'name'   => 'payment_methods',
                'values' => theme_option('payment_methods'),
            ],
        ])
        ->setSection([
            'title'      => __('Social links'),
            'desc'       => __('Social links'),
            'id'         => 'opt-text-subsection-social-links',
            'subsection' => true,
            'icon'       => 'fa fa-share-alt',
        ])
        ->setField([
            'id'         => 'social_links',
            'section_id' => 'opt-text-subsection-social-links',
            'type'       => 'repeater',
            'label'      => __('Social links'),
            'attributes' => [
                'name'   => 'social_links',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Name'),
                        'attributes' => [
                            'name'    => 'social-name',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'themeIcon',
                        'label'      => __('Icon'),
                        'attributes' => [
                            'name'    => 'social-icon',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('URL'),
                        'attributes' => [
                            'name'    => 'social-url',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'customColor',
                        'label'      => __('Color'),
                        'attributes' => [
                            'name'    => 'social-color',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Header messages'),
            'desc'       => __('Header messages'),
            'id'         => 'opt-text-subsection-header-messages',
            'subsection' => true,
            'icon'       => 'fa fa-bell',
        ])
        ->setField([
            'id'         => 'header_messages',
            'section_id' => 'opt-text-subsection-header-messages',
            'type'       => 'repeater',
            'label'      => __('Header messages'),
            'clean_tags' => false,
            'attributes' => [
                'name'   => 'header_messages',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'themeIcon',
                        'label'      => __('Icon'),
                        'attributes' => [
                            'name'    => 'icon',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Message'),
                        'attributes' => [
                            'name'    => 'message',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Link'),
                        'attributes' => [
                            'name'    => 'link',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Link Text'),
                        'attributes' => [
                            'name'    => 'link_text',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Contact info boxes'),
            'desc'       => __('Contact info boxes'),
            'id'         => 'opt-contact',
            'subsection' => false,
            'icon'       => 'fa fa-info-circle',
            'fields'     => [],
        ])
        ->setField([
            'id'         => 'contact_info_boxes',
            'section_id' => 'opt-contact',
            'type'       => 'repeater',
            'label'      => __('Contact info boxes'),
            'attributes' => [
                'name'   => 'contact_info_boxes',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Name'),
                        'attributes' => [
                            'name'    => 'name',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Address'),
                        'attributes' => [
                            'name'    => 'address',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Phone'),
                        'attributes' => [
                            'name'    => 'phone',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'email',
                        'label'      => __('Email'),
                        'attributes' => [
                            'name'    => 'email',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->setField([
            'id'         => 'blog_single_layout',
            'section_id' => 'opt-text-subsection-blog',
            'type'       => 'customSelect',
            'label'      => __('Default Blog Single Layout'),
            'attributes' => [
                'name'    => 'blog_single_layout',
                'list'    => get_blog_single_layouts(),
                'value'   => 'blog-right-sidebar',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'product_single_layout',
            'section_id' => 'opt-text-subsection-ecommerce',
            'type'       => 'customSelect',
            'label'      => __('Default Product Single Layout'),
            'attributes' => [
                'name'    => 'product_single_layout',
                'list'    => get_product_single_layouts(),
                'value'   => 'product-right-sidebar',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'product_list_layout',
            'section_id' => 'opt-text-subsection-ecommerce',
            'type'       => 'customSelect',
            'label'      => __('Default Product List Layout'),
            'attributes' => [
                'name'    => 'product_list_layout',
                'list'    => get_product_single_layouts(),
                'value'   => 'product-full-width',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Style'),
            'desc'       => __('Style of page'),
            'id'         => 'opt-text-subsection-style',
            'subsection' => true,
            'icon'       => 'fa fa-bars',
        ])
        ->setField([
            'id'         => 'font_text',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'googleFonts',
            'label'      => __('Font text'),
            'attributes' => [
                'name'  => 'font_text',
                'value' => 'Poppins',
            ],
        ])
        ->setField([
            'id'         => 'header_style',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customSelect',
            'label'      => __('Header style'),
            'attributes' => [
                'name'    => 'header_style',
                'list'    => get_layout_header_styles(),
                'value'   => '',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'color_brand',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Color brand'),
            'attributes' => [
                'name'  => 'color_brand',
                'value' => '#5897fb',
            ],
        ])
        ->setField([
            'id'         => 'color_brand_2',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Color brand 2'),
            'attributes' => [
                'name'  => 'color_brand_2',
                'value' => '#3256e0',
            ],
        ])
        ->setField([
            'id'         => 'color_primary',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Primary color'),
            'attributes' => [
                'name'  => 'color_primary',
                'value' => '#3f81eb',
            ],
        ])
        ->setField([
            'id'         => 'color_secondary',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Secondary color'),
            'attributes' => [
                'name'  => 'color_secondary',
                'value' => '#41506b',
            ],
        ])
        ->setField([
            'id'         => 'color_warning',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Warning color'),
            'attributes' => [
                'name'  => 'color_warning',
                'value' => '#ffb300',
            ],
        ])
        ->setField([
            'id'         => 'color_danger',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Danger color'),
            'attributes' => [
                'name'  => 'color_danger',
                'value' => '#ff3551',
            ],
        ])
        ->setField([
            'id'         => 'color_success',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Success color'),
            'attributes' => [
                'name'  => 'color_success',
                'value' => '#3ed092',
            ],
        ])
        ->setField([
            'id'         => 'color_info',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Info color'),
            'attributes' => [
                'name'  => 'color_info',
                'value' => '#18a1b7',
            ],
        ])
        ->setField([
            'id'         => 'color_text',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Text color'),
            'attributes' => [
                'name'  => 'color_text',
                'value' => '#4f5d77',
            ],
        ])
        ->setField([
            'id'         => 'color_heading',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Heading color'),
            'attributes' => [
                'name'  => 'color_heading',
                'value' => '#222222',
            ],
        ])
        ->setField([
            'id'         => 'color_grey_1',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Grey 1'),
            'attributes' => [
                'name'  => 'color_grey_1',
                'value' => '#111111',
            ],
        ])
        ->setField([
            'id'         => 'color_grey_2',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Grey 2'),
            'attributes' => [
                'name'  => 'color_grey_2',
                'value' => '#242424',
            ],
        ])
        ->setField([
            'id'         => 'color_grey_4',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Grey 4'),
            'attributes' => [
                'name'  => 'color_grey_4',
                'value' => '#90908e',
            ],
        ])
        ->setField([
            'id'         => 'color_grey_9',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Grey 9'),
            'attributes' => [
                'name'  => 'color_grey_9',
                'value' => '#f4f5f9',
            ],
        ])
        ->setField([
            'id'         => 'color_muted',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Muted color'),
            'attributes' => [
                'name'  => 'color_muted',
                'value' => '#8e8e90',
            ],
        ])
        ->setField([
            'id'         => 'color_body',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'customColor',
            'label'      => __('Body color'),
            'attributes' => [
                'name'  => 'color_body',
                'value' => '#4f5d77',
            ],
        ])
        ->setField([
            'id'         => 'facebook_comment_enabled_in_product',
            'section_id' => 'opt-text-subsection-facebook-integration',
            'type'       => 'customSelect',
            'label'      => __('Enable Facebook comment in product detail page?'),
            'attributes' => [
                'name'    => 'facebook_comment_enabled_in_product',
                'list'    => [
                    'no'  => trans('core/base::base.no'),
                    'yes' => trans('core/base::base.yes'),
                ],
                'value'   => 'no',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ]);
});
