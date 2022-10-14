<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Widget\Models\Widget as WidgetModel;
use Theme;

class WidgetSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WidgetModel::truncate();

        $data = [
            'en_US' => [
                [
                    'widget_id'  => 'SiteInfoWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 0,
                    'data'       => [
                        'id'   => 'SiteInfoWidget',
                        'name' => 'Site information',
                    ],
                ],
                [
                    'widget_id'  => 'CustomMenuWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 1,
                    'data'       => [
                        'id'      => 'CustomMenuWidget',
                        'name'    => 'Categories',
                        'menu_id' => 'product-categories',
                    ],
                ],
                [
                    'widget_id'  => 'CustomMenuWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 2,
                    'data'       => [
                        'id'      => 'CustomMenuWidget',
                        'name'    => 'Information',
                        'menu_id' => 'information',
                    ],
                ],
                [
                    'widget_id'  => 'PaymentMethodsWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 3,
                    'data'       => [
                        'id'          => 'PaymentMethodsWidget',
                        'name'        => 'Payments',
                        'description' => 'Secured Payment Gateways',
                        'image'       => 'general/payment-methods.png',
                    ],
                ],

                [
                    'widget_id'  => 'BlogSearchWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 0,
                    'data'       => [
                        'id'   => 'BlogSearchWidget',
                        'name' => 'Search',
                    ],
                ],
                [
                    'widget_id'  => 'BlogCategoriesWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 1,
                    'data'       => [
                        'id'   => 'BlogCategoriesWidget',
                        'name' => 'Categories',
                    ],
                ],
                [
                    'widget_id'  => 'RecentPostsWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 2,
                    'data'       => [
                        'id'   => 'RecentPostsWidget',
                        'name' => 'Recent Posts',
                    ],
                ],
                [
                    'widget_id'  => 'TagsWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 4,
                    'data'       => [
                        'id'   => 'TagsWidget',
                        'name' => 'Popular Tags',
                    ],
                ],

                [
                    'widget_id'  => 'ProductCategoriesWidget',
                    'sidebar_id' => 'product_sidebar',
                    'position'   => 1,
                    'data'       => [
                        'id'   => 'ProductCategoriesWidget',
                        'name' => 'Categories',
                    ],
                ],
                [
                    'widget_id'  => 'FeaturedProductsWidget',
                    'sidebar_id' => 'product_sidebar',
                    'position'   => 2,
                    'data'       => [
                        'id'   => 'FeaturedProductsWidget',
                        'name' => 'Featured Products',
                    ],
                ],
                [
                    'widget_id'  => 'FeaturedBrandsWidget',
                    'sidebar_id' => 'product_sidebar',
                    'position'   => 3,
                    'data'       => [
                        'id'   => 'FeaturedBrandsWidget',
                        'name' => 'Manufacturers',
                    ],
                ],
            ],
            'vi'    => [
                [
                    'widget_id'  => 'SiteInfoWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 0,
                    'data'       => [
                        'id'   => 'SiteInfoWidget',
                        'name' => 'Về chúng tôi',
                    ],
                ],
                [
                    'widget_id'  => 'CustomMenuWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 1,
                    'data'       => [
                        'id'      => 'CustomMenuWidget',
                        'name'    => 'Danh mục sản phẩm',
                        'menu_id' => 'danh-muc-san-pham',
                    ],
                ],
                [
                    'widget_id'  => 'CustomMenuWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 2,
                    'data'       => [
                        'id'      => 'CustomMenuWidget',
                        'name'    => 'Thông tin',
                        'menu_id' => 'thong-tin',
                    ],
                ],
                [
                    'widget_id'  => 'PaymentMethodsWidget',
                    'sidebar_id' => 'footer_sidebar',
                    'position'   => 3,
                    'data'       => [
                        'id'          => 'PaymentMethodsWidget',
                        'name'        => 'Thanh toán',
                        'description' => 'Cổng thanh toán an toàn',
                        'image'       => 'general/payment-methods.png',
                    ],
                ],

                [
                    'widget_id'  => 'BlogSearchWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 0,
                    'data'       => [
                        'id'   => 'BlogSearchWidget',
                        'name' => 'Tìm kiếm',
                    ],
                ],
                [
                    'widget_id'  => 'BlogCategoriesWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 1,
                    'data'       => [
                        'id'   => 'BlogCategoriesWidget',
                        'name' => 'Danh mục',
                    ],
                ],
                [
                    'widget_id'  => 'RecentPostsWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 2,
                    'data'       => [
                        'id'   => 'RecentPostsWidget',
                        'name' => 'Bài viết gần đây',
                    ],
                ],
                [
                    'widget_id'  => 'TagsWidget',
                    'sidebar_id' => 'primary_sidebar',
                    'position'   => 4,
                    'data'       => [
                        'id'   => 'TagsWidget',
                        'name' => 'Tags phổ biến',
                    ],
                ],

                [
                    'widget_id'  => 'ProductCategoriesWidget',
                    'sidebar_id' => 'product_sidebar',
                    'position'   => 1,
                    'data'       => [
                        'id'   => 'ProductCategoriesWidget',
                        'name' => 'Danh mục sản phẩm',
                    ],
                ],
                [
                    'widget_id'  => 'FeaturedProductsWidget',
                    'sidebar_id' => 'product_sidebar',
                    'position'   => 2,
                    'data'       => [
                        'id'   => 'FeaturedProductsWidget',
                        'name' => 'Sản phẩm nổi bật',
                    ],
                ],
                [
                    'widget_id'  => 'FeaturedBrandsWidget',
                    'sidebar_id' => 'product_sidebar',
                    'position'   => 3,
                    'data'       => [
                        'id'   => 'FeaturedBrandsWidget',
                        'name' => 'Nhà cung cấp',
                    ],
                ],
            ],
        ];

        $theme = Theme::getThemeName();

        foreach ($data as $locale => $widgets) {
            foreach ($widgets as $item) {
                $item['theme'] = $locale == 'en_US' ? $theme : ($theme . '-' . $locale);
                WidgetModel::create($item);
            }
        }
    }
}
