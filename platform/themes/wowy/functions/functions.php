<?php

use Botble\Base\Forms\FormAbstract;
use Kris\LaravelFormBuilder\FormHelper;
use Theme\Wowy\Fields\ThemeIconField;
use Botble\Ads\Models\Ads;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Blog\Models\Post;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Page\Models\Page;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;

register_page_template([
    'full-width'         => __('Full width'),
    'homepage'           => __('Homepage'),
    'blog-right-sidebar' => __('Blog Right Sidebar'),
    'blog-left-sidebar'  => __('Blog Left Sidebar'),
    'blog-full-width'    => __('Blog Full Width'),
]);

register_sidebar([
    'id'          => 'footer_sidebar',
    'name'        => __('Footer sidebar'),
    'description' => __('Widgets in footer of page'),
]);

register_sidebar([
    'id'          => 'product_sidebar',
    'name'        => __('Product sidebar'),
    'description' => __('Widgets in the product page'),
]);

Form::component('themeIcon', Theme::getThemeNamespace() . '::partials.forms.fields.icons-field', [
    'name',
    'value'      => null,
    'attributes' => [],
]);

add_filter('form_custom_fields', function (FormAbstract $form, FormHelper $formHelper) {
    if (!$formHelper->hasCustomField('themeIcon')) {
        $form->addCustomField('themeIcon', ThemeIconField::class);
    }

    return $form;
}, 29, 2);

RvMedia::setUploadPathAndURLToPublic();

RvMedia::addSize('medium', 800, 800)
    ->addSize('product-thumb', 400, 400);

if (is_plugin_active('ecommerce')) {
    app()->booted(function () {
        ProductCategory::resolveRelationUsing('icon', function ($model) {
            return $model->morphOne(MetaBoxModel::class, 'reference')->where('meta_key', 'icon');
        });

        if (is_plugin_active('language-advanced')) {
            LanguageAdvancedManager::registerModule(FlashSale::class, ['name', 'subtitle']);
        }
    });
}

if (!function_exists('get_currencies_json')) {
    /**
     * @return array
     */
    function get_currencies_json()
    {
        $currency = get_application_currency();
        $numberAfterDot = $currency->decimals ?: 0;

        return [
            'display_big_money'    => config('plugins.ecommerce.general.display_big_money_in_million_billion'),
            'billion'              => __('billion'),
            'million'              => __('million'),
            'is_prefix_symbol'     => $currency->is_prefix_symbol,
            'symbol'               => $currency->symbol,
            'title'                => $currency->title,
            'decimal_separator'    => get_ecommerce_setting('decimal_separator', '.'),
            'thousands_separator'  => get_ecommerce_setting('thousands_separator', ','),
            'number_after_dot'     => $numberAfterDot,
            'show_symbol_or_title' => true,
        ];
    }
}

if (!function_exists('get_blog_single_layouts')) {
    /**
     * @return string[]
     */
    function get_blog_single_layouts(): array
    {
        return [
            ''                   => __('Inherit'),
            'blog-right-sidebar' => __('Blog Right Sidebar'),
            'blog-left-sidebar'  => __('Blog Left Sidebar'),
            'blog-full-width'    => __('Full width'),
        ];
    }
}

if (!function_exists('get_product_single_layouts')) {
    /**
     * @return string[]
     */
    function get_product_single_layouts(): array
    {
        return [
            ''                      => __('Inherit'),
            'product-right-sidebar' => __('Product Right Sidebar'),
            'product-left-sidebar'  => __('Product Left Sidebar'),
            'product-full-width'    => __('Product Full Width'),
        ];
    }
}

if (!function_exists('get_layout_header_styles')) {
    /**
     * @return string[]
     */
    function get_layout_header_styles(): array
    {
        return [
            'header-style-1' => __('Default'),
            'header-style-2' => __('Header style 2'),
            'header-style-3' => __('Header style 3'),
            'header-style-4' => __('Header style 4'),
        ];
    }
}

if (!function_exists('get_simple_slider_styles')) {
    /**
     * @return string[]
     */
    function get_simple_slider_styles(): array
    {
        return [
            'style-1' => __('Default - Full width'),
            'style-2' => __('Full width - text center'),
            'style-3' => __('With Ads'),
            'style-4' => __('Limit width'),
        ];
    }
}

if (!function_exists('get_time_to_read')) {
    /**
     * @param Post $post
     * @return string
     */
    function get_time_to_read(Post $post)
    {
        $timeToRead = MetaBox::getMetaData($post, 'time_to_read', true);

        if ($timeToRead) {
            return number_format($timeToRead);
        }

        return number_format(strlen(strip_tags($post->content)) / 300);
    }
}

add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
    switch (get_class($data)) {
        case SimpleSliderItem::class:
            $buttonText = MetaBox::getMetaData($data, 'button_text', true);
            $subtitle = MetaBox::getMetaData($data, 'subtitle', true);
            $highlightText = MetaBox::getMetaData($data, 'highlight_text', true);

            $form
                ->addAfter('link', 'button_text', 'text', [
                    'label'      => __('Button text'),
                    'label_attr' => ['class' => 'control-label'],
                    'value'      => $buttonText,
                    'attr'       => [
                        'placeholder' => __('Ex: Shop now'),
                    ],
                ])
                ->addBefore('title', 'subtitle', 'text', [
                    'label'      => __('Subtitle'),
                    'label_attr' => ['class' => 'control-label'],
                    'value'      => $subtitle,
                    'attr'       => [
                        'placeholder' => __('Text to highlight'),
                    ],
                ])
                ->addAfter('title', 'highlight_text', 'text', [
                    'label'      => __('Highlight text'),
                    'label_attr' => ['class' => 'control-label'],
                    'value'      => $highlightText,
                    'attr'       => [
                        'placeholder' => __('Text to highlight'),
                    ],
                ]);
            break;

        case Ads::class:
            $buttonText = MetaBox::getMetaData($data, 'button_text', true);
            $subtitle = MetaBox::getMetaData($data, 'subtitle', true);

            $form
                ->addAfter('key', 'button_text', 'text', [
                    'label'      => __('Button text'),
                    'label_attr' => ['class' => 'control-label'],
                    'value'      => $buttonText,
                    'attr'       => [
                        'placeholder' => __('Ex: Shop now'),
                    ],
                ])
                ->addBefore('key', 'subtitle', 'textarea', [
                    'label'      => __('Subtitle'),
                    'label_attr' => ['class' => 'control-label'],
                    'value'      => $subtitle,
                    'attr'       => [
                        'placeholder' => __('Text to highlight'),
                        'rows'        => 3,
                    ],
                ])
                ->setBreakFieldPoint('image');
            break;

        case FlashSale::class:
            $subtitle = MetaBox::getMetaData($data, 'subtitle', true);
            $image = MetaBox::getMetaData($data, 'image', true);

            $form
                ->addAfter('name', 'subtitle', 'text', [
                    'label'      => __('Subtitle'),
                    'label_attr' => ['class' => 'control-label'],
                    'value'      => $subtitle,
                    'attr'       => [
                        'placeholder' => __('Text to highlight'),
                    ],
                ])
                ->addAfter('end_date', 'image', 'mediaImage', [
                    'label'      => __('Image'),
                    'label_attr' => ['class' => 'control-label'],
                    'value'      => $image,
                ]);
            break;
    }

    return $form;
}, 124, 3);

add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
    switch (get_class($object)) {
        case Page::class:
            if ($context == 'top') {
                MetaBox::addMetaBox(
                    'additional_page_fields',
                    __('Appearance'),
                    function () {
                        $headerStyle = null;
                        $expandingProductCategories = 'no';
                        $page = null;
                        $args = func_get_args();
                        if (!empty($args[0])) {
                            $page = $args[0];
                            $headerStyle = MetaBox::getMetaData($args[0], 'header_style', true);
                            $expandingProductCategories = MetaBox::getMetaData(
                                $args[0],
                                'expanding_product_categories_on_the_homepage',
                                true
                            );
                        }

                        if (!$headerStyle && theme_option('header_style')) {
                            $headerStyle = theme_option('header_style');
                        }

                        return Theme::partial(
                            'additional-page-fields',
                            compact('headerStyle', 'expandingProductCategories', 'page')
                        );
                    },
                    get_class($object),
                    $context
                );
            }
            break;

        case SimpleSlider::class:
            if ($context == 'top') {
                MetaBox::addMetaBox(
                    'additional_simple_slider_fields',
                    __('Appearance'),
                    function () {
                        $style = '';
                        $args = func_get_args();
                        if (!empty($args[0])) {
                            $style = MetaBox::getMetaData($args[0], 'simple_slider_style', true);
                        }

                        return Theme::partial('additional-simple-slider-fields', compact('style'));
                    },
                    get_class($object),
                    $context
                );
            }
            break;

        case ProductCategory::class:
            if ($context == 'advanced') {
                MetaBox::addMetaBox('additional_product_category_fields', __('Addition Information'), function () {
                    $icon = null;
                    $iconImage = null;
                    $args = func_get_args();
                    if (!empty($args[0])) {
                        $icon = MetaBox::getMetaData($args[0], 'icon', true);
                        $iconImage = MetaBox::getMetaData($args[0], 'icon_image', true);
                    }

                    return Theme::partial('product-category-fields', compact('icon', 'iconImage'));
                }, get_class($object), $context);
            }
            break;

        case Product::class:
            if ($context == 'top') {
                MetaBox::addMetaBox(
                    'additional_product_fields',
                    __('Addition Information'),
                    function () {
                        $layout = null;
                        $args = func_get_args();
                        if (!empty($args[0])) {
                            $layout = MetaBox::getMetaData($args[0], 'layout', true);
                        }

                        if (!$layout && theme_option('product_single_layout')) {
                            $layout = theme_option('product_single_layout');
                        }

                        return Theme::partial('additional-product-fields', compact('layout'));
                    },
                    get_class($object),
                    $context
                );
            }
            break;

        case Post::class:
            if ($context == 'top') {
                MetaBox::addMetaBox(
                    'additional_post_fields',
                    __('Addition Information'),
                    function () {
                        $timeToRead = null;
                        $layout = null;
                        $args = func_get_args();
                        if (!empty($args[0])) {
                            $timeToRead = MetaBox::getMetaData($args[0], 'time_to_read', true);
                            $layout = MetaBox::getMetaData($args[0], 'layout', true);
                        }

                        if (!$layout && theme_option('blog_single_layout')) {
                            $layout = theme_option('blog_single_layout');
                        }

                        return Theme::partial('blog-post-fields', compact('timeToRead', 'layout'));
                    },
                    get_class($object),
                    $context
                );
            }
            break;
    }
}, 75, 2);

add_action([BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT], function ($type, $request, $object) {
    switch (get_class($object)) {
        case Page::class:
            if ($request->has('header_style')) {
                $style = $request->input('header_style');
                if (in_array($style, array_keys(get_layout_header_styles()))) {
                    MetaBox::saveMetaBoxData($object, 'header_style', $style);
                }
            }

            if ($request->has('expanding_product_categories_on_the_homepage')) {
                if ($request->input('expanding_product_categories_on_the_homepage')) {
                    MetaBox::saveMetaBoxData(
                        $object,
                        'expanding_product_categories_on_the_homepage',
                        $request->input('expanding_product_categories_on_the_homepage')
                    );
                }
            }

            break;
        case SimpleSlider::class:
            if ($request->has('simple_slider_style')) {
                $style = $request->input('simple_slider_style');

                if (in_array($style, array_keys(get_simple_slider_styles()))) {
                    MetaBox::saveMetaBoxData($object, 'simple_slider_style', $style);
                }
            }

            break;

        case SimpleSliderItem::class:
            if ($request->has('button_text')) {
                MetaBox::saveMetaBoxData($object, 'button_text', $request->input('button_text'));
            }

            if ($request->has('subtitle')) {
                MetaBox::saveMetaBoxData($object, 'subtitle', $request->input('subtitle'));
            }

            if ($request->has('highlight_text')) {
                MetaBox::saveMetaBoxData($object, 'highlight_text', $request->input('highlight_text'));
            }

            break;

        case ProductCategory::class:
            if ($request->has('icon')) {
                MetaBox::saveMetaBoxData($object, 'icon', $request->input('icon'));
            }

            if ($request->has('icon_image')) {
                MetaBox::saveMetaBoxData($object, 'icon_image', $request->input('icon_image'));
            }

            break;

        case Product::class:
            if ($request->has('layout')) {
                MetaBox::saveMetaBoxData($object, 'layout', $request->input('layout'));
            }

            break;

        case Post::class:
            if ($request->has('time_to_read')) {
                MetaBox::saveMetaBoxData($object, 'time_to_read', $request->input('time_to_read'));
            }

            if ($request->has('layout')) {
                MetaBox::saveMetaBoxData($object, 'layout', $request->input('layout'));
            }

            break;

        case FlashSale::class:
            if ($request->has('subtitle')) {
                MetaBox::saveMetaBoxData($object, 'subtitle', $request->input('subtitle'));
            }

            if ($request->has('image')) {
                MetaBox::saveMetaBoxData($object, 'image', $request->input('image'));
            }

            break;

        case Ads::class:
            if ($request->has('button_text')) {
                MetaBox::saveMetaBoxData($object, 'button_text', $request->input('button_text'));
            }

            if ($request->has('subtitle')) {
                MetaBox::saveMetaBoxData($object, 'subtitle', $request->input('subtitle'));
            }

            break;
    }
}, 75, 3);

if (!function_exists('theme_get_autoplay_speed_options')) {
    /**
     * @return int[]
     */
    function theme_get_autoplay_speed_options(): array
    {
        return array_combine([2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000], [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]);
    }
}

app()->booted(function () {
    if (is_plugin_active('ads') && is_plugin_active('language-advanced')) {
        LanguageAdvancedManager::registerModule(Ads::class, [
            'name',
            'image',
            'url',
            'subtitle',
            'button_text',
        ]);
    }
});
