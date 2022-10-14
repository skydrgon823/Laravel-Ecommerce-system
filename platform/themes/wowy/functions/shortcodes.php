<?php

use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Botble\Theme\Supports\ThemeSupport;
use Illuminate\Support\Collection;
use Theme\Wowy\Http\Resources\ProductCollectionResource;

app()->booted(function () {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('simple-slider')) {
        add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
            return Theme::getThemeNamespace() . '::partials.shortcodes.sliders.main';
        }, 120);
    }

    add_shortcode('site-features', __('Site features'), __('Site features'), function ($shortcode) {
        return Theme::partial('shortcodes.site-features', compact('shortcode'));
    });

    shortcode()->setAdminConfig('site-features', function ($attributes) {
        return Theme::partial('shortcodes.site-features-admin-config', compact('attributes'));
    });

    if (is_plugin_active('ecommerce')) {
        add_shortcode(
            'featured-product-categories',
            __('Featured Product Categories'),
            __('Featured Product Categories'),
            function ($shortcode) {
                return Theme::partial('shortcodes.featured-product-categories', [
                    'title' => $shortcode->title,
                ]);
            }
        );

        shortcode()->setAdminConfig('featured-product-categories', function ($attributes) {
            return Theme::partial('shortcodes.featured-product-categories-admin-config', compact('attributes'));
        });

        add_shortcode('featured-products', __('Featured products'), __('Featured products'), function ($shortcode) {
            if (!is_plugin_active('ecommerce')) {
                return null;
            }

            return Theme::partial('shortcodes.featured-products', [
                'title'       => $shortcode->title,
                'description' => $shortcode->description,
                'limit'       => (int)$shortcode->limit ?: 8,
            ]);
        });

        shortcode()->setAdminConfig('featured-products', function ($attributes) {
            return Theme::partial('shortcodes.featured-products-admin-config', compact('attributes'));
        });

        add_shortcode('flash-sale', __('Flash sale'), __('Flash sale'), function ($shortcode) {
            $flashSale = app(FlashSaleInterface::class)
                ->getModel()
                ->notExpired()
                ->with(['products'])
                ->first();

            if (!$flashSale || !$flashSale->products->count()) {
                return null;
            }

            return Theme::partial('shortcodes.flash-sale', [
                'title'     => $shortcode->title,
                'showPopup' => $shortcode->show_popup,
                'limit'     => (int)$shortcode->limit ?: 2,
                'flashSale'  => $flashSale,
            ]);
        });

        shortcode()->setAdminConfig('flash-sale', function ($attributes) {
            return Theme::partial('shortcodes.flash-sale-admin-config', compact('attributes'));
        });

        add_shortcode(
            'product-collections',
            __('Product Collections'),
            __('Product Collections'),
            function ($shortcode) {
                $productCollections = get_product_collections(
                    ['status' => BaseStatusEnum::PUBLISHED],
                    [],
                    ['id', 'name', 'slug']
                );

                return Theme::partial('shortcodes.product-collections', [
                    'title'              => $shortcode->title,
                    'productCollections' => ProductCollectionResource::collection($productCollections),
                ]);
            }
        );

        shortcode()->setAdminConfig('product-collections', function ($attributes) {
            return Theme::partial('shortcodes.product-collections-admin-config', compact('attributes'));
        });

        add_shortcode(
            'product-category-products',
            __('Product category products'),
            __('Product category products'),
            function ($shortcode) {
                $category = app(ProductCategoryInterface::class)->getFirstBy([
                    'status' => BaseStatusEnum::PUBLISHED,
                    'id'     => (int)$shortcode->category_id,
                ], ['*'], [
                    'activeChildren' => function ($query) {
                        $query->limit(3);
                    },
                ]);

                if (!$category) {
                    return null;
                }

                return Theme::partial('shortcodes.product-category-products', compact('category'));
            }
        );

        shortcode()->setAdminConfig('product-category-products', function ($attributes) {
            $categories = app(ProductCategoryInterface::class)->pluck(
                'name',
                'id',
                ['status' => BaseStatusEnum::PUBLISHED]
            );

            return Theme::partial(
                'shortcodes.product-category-products-admin-config',
                compact('categories', 'attributes')
            );
        });

        add_shortcode('featured-brands', __('Featured Brands'), __('Featured Brands'), function ($shortcode) {
            return Theme::partial('shortcodes.featured-brands', [
                'title' => $shortcode->title,
            ]);
        });

        shortcode()->setAdminConfig('featured-brands', function ($attributes) {
            return Theme::partial('shortcodes.featured-brands-admin-config', compact('attributes'));
        });
    }

    if (is_plugin_active('ads')) {
        add_shortcode('theme-ads', __('Theme ads'), __('Theme ads'), function ($shortcode) {
            $keys = get_ads_keys_from_shortcode($shortcode);

            return display_ads($keys);
        });

        shortcode()->setAdminConfig('theme-ads', function ($attributes) {
            $ads = app(AdsInterface::class)->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->notExpired()
                ->get();

            return Theme::partial('shortcodes.ads.config-in-admin', compact('ads', 'attributes'));
        });

        AdsManager::load();

        /**
         * @param string|BaseModel $ads
         * @return string|null
         */
        function display_ad($ads, $class = '')
        {
            if (!($ads instanceof BaseModel)) {
                $ads = AdsManager::getData()
                    ->where('key', $ads)
                    ->first();
            }

            if (!$ads || !$ads->image) {
                return null;
            }

            if ($ads->location &&
                $ads->location != 'not_set' &&
                view()->exists(Theme::getThemeNamespace() . '::partials.shortcodes.ads.' . $ads->location)) {
                return Theme::partial('shortcodes.ads.' . $ads->location, compact('ads', 'class'));
            }

            return Theme::partial('shortcodes.ads.item', compact('ads', 'class'));
        }

        /**
         * @param $shortcode
         * @return array|Collection
         */
        function get_ads_keys_from_shortcode($shortcode)
        {
            $keys = collect($shortcode->toArray())
                ->sortKeys()
                ->filter(function ($value, $key) use ($shortcode) {
                    return Str::startsWith($key, 'ads_') ||
                        ($shortcode->name == 'theme-ads' && Str::startsWith($key, 'key_'));
                });

            return array_filter($keys->toArray() + [$shortcode->ads]);
        }

        /**
         * @param array $keys
         * @return string
         */
        function display_ads(array $keys)
        {
            $keys = collect($keys);

            return Theme::partial('shortcodes.ads.items', compact('keys'));
        }

        if (is_plugin_active('simple-slider')) {
            add_filter(SHORTCODE_REGISTER_CONTENT_IN_ADMIN, function ($data, $key, $attributes) {
                if ($key == 'simple-slider') {
                    $ads = app(AdsInterface::class)->getModel()
                        ->where('status', BaseStatusEnum::PUBLISHED)
                        ->notExpired()
                        ->get();

                    return $data . Theme::partial('shortcodes.includes.autoplay-settings', compact('attributes')) . Theme::partial('shortcodes.ads.config-in-admin', compact('ads', 'attributes'));
                }

                return $data;
            }, 50, 3);
        }
    }

    if (is_plugin_active('blog')) {
        add_shortcode('featured-news', __('Featured News'), __('Featured News'), function ($shortcode) {
            return Theme::partial('shortcodes.featured-news', ['title' => $shortcode->title]);
        });

        shortcode()->setAdminConfig('featured-news', function ($attributes) {
            return Theme::partial('shortcodes.featured-news-admin-config', compact('attributes'));
        });
    }

    if (is_plugin_active('contact')) {
        add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
            return Theme::getThemeNamespace() . '::partials.shortcodes.contact-form';
        }, 120);
    }

    if (is_plugin_active('newsletter')) {
        add_shortcode('newsletter-form', __('Newsletter Form'), __('Newsletter Form'), function ($shortcode) {
            return Theme::partial('shortcodes.newsletter-form', [
                'title'       => $shortcode->title,
                'description' => $shortcode->description,
            ]);
        });

        shortcode()->setAdminConfig('newsletter-form', function ($attributes) {
            return Theme::partial('shortcodes.newsletter-form-admin-config', compact('attributes'));
        });
    }

    add_shortcode('our-offices', __('Our offices'), __('Our offices'), function () {
        return Theme::partial('shortcodes.our-offices');
    });

    shortcode()->setAdminConfig('our-offices', function ($attributes) {
        return Theme::partial('shortcodes.our-offices-admin-config', compact('attributes'));
    });

    if (is_plugin_active('faq')) {
        add_shortcode('faqs', __('FAQs'), __('List of FAQs'), function ($shortcode) {
            $params = [
                'condition' => [
                    'status' => BaseStatusEnum::PUBLISHED,
                ],
                'with'      => [
                    'faqs' => function ($query) {
                        $query->where('status', BaseStatusEnum::PUBLISHED);
                    },
                ],
                'order_by'  => [
                    'faq_categories.order'      => 'ASC',
                    'faq_categories.created_at' => 'DESC',
                ],
            ];

            if ($shortcode->category_id) {
                $params['condition']['id'] = $shortcode->category_id;
            }

            $categories = app(FaqCategoryInterface::class)->advancedGet($params);

            return Theme::partial('shortcodes.faqs', compact('categories'));
        });

        shortcode()->setAdminConfig('faqs', function ($attributes) {
            $categories = app(FaqCategoryInterface::class)->pluck('name', 'id', ['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial('shortcodes.faqs-admin-config', compact('attributes', 'categories'));
        });
    }
});
