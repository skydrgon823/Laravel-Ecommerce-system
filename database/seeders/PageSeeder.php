<?php

namespace Database\Seeders;

use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\LanguageAdvanced\Models\PageTranslation;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Faker\Factory;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MetaBox;
use SlugHelper;

class PageSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $pages = [
            [
                'name'     => 'Homepage',
                'content'  =>
                    Html::tag('div', '[simple-slider key="home-slider-1" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]') .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Free Shipping" subtitle1="Orders $50 or more" icon2="general/icon-purchase.png" title2="Free Returns" subtitle2="Within 30 days" icon3="general/icon-bag.png" title3="Get 20% Off 1 Item" subtitle3="When you sign up" icon4="general/icon-operator.png" title4="Support Center" subtitle4="24/7 amazing services"][/site-features]'
                    ) .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Top Categories"][/featured-product-categories]'
                    ) .
                    Html::tag('div', '[product-collections title="Exclusive Products"][/product-collections]') .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag('div', '[featured-products title="Featured products"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Featured Brands"][/featured-brands]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Visit Our Blog"][/featured-news]'
                    )
                ,
                'template' => 'homepage',
            ],
            [
                'name'                                         => 'Homepage 2',
                'content'                                      =>
                    Html::tag('div', '[simple-slider key="home-slider-2" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]') .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag('div', '[product-collections title="Exclusive Products"][/product-collections]') .
                    Html::tag('div', '[theme-ads ads_1="IZ6WU8KUALYF"][/theme-ads]') .
                    Html::tag('div', '[featured-products title="Featured products"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Featured Brands"][/featured-brands]'
                    ) .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Top Categories"][/featured-product-categories]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Visit Our Blog"][/featured-news]'
                    ) .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Free Shipping" subtitle1="Orders $50 or more" icon2="general/icon-purchase.png" title2="Free Returns" subtitle2="Within 30 days" icon3="general/icon-bag.png" title3="Get 20% Off 1 Item" subtitle3="When you sign up" icon4="general/icon-operator.png" title4="Support Center" subtitle4="24/7 amazing services"][/site-features]'
                    )
                ,
                'template'                                     => 'homepage',
                'header_style'                                 => 'header-style-2',
                'expanding_product_categories_on_the_homepage' => 'yes',
            ],
            [
                'name'         => 'Homepage 3',
                'content'      =>
                    Html::tag(
                        'div',
                        '[simple-slider key="home-slider-3" ads_1="ILSDKVYFGXPJ" ads_2="IZ6WU8KUALYE" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]'
                    ) .
                    Html::tag('div', '[product-collections title="Exclusive Products"][/product-collections]') .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Free Shipping" subtitle1="Orders $50 or more" icon2="general/icon-purchase.png" title2="Free Returns" subtitle2="Within 30 days" icon3="general/icon-bag.png" title3="Get 20% Off 1 Item" subtitle3="When you sign up" icon4="general/icon-operator.png" title4="Support Center" subtitle4="24/7 amazing services"][/site-features]'
                    ) .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Top Categories"][/featured-product-categories]'
                    ) .
                    Html::tag('div', '[featured-products title="Featured products"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag('div', '[theme-ads ads_1="IZ6WU8KUALYF"][/theme-ads]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Featured Brands"][/featured-brands]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Visit Our Blog"][/featured-news]'
                    )
                ,
                'template'     => 'homepage',
                'header_style' => 'header-style-3',
            ],
            [
                'name'         => 'Homepage 4',
                'content'      =>
                    Html::tag('div', '[simple-slider key="home-slider-4" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]') .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Free Shipping" subtitle1="Orders $50 or more" icon2="general/icon-purchase.png" title2="Free Returns" subtitle2="Within 30 days" icon3="general/icon-bag.png" title3="Get 20% Off 1 Item" subtitle3="When you sign up" icon4="general/icon-operator.png" title4="Support Center" subtitle4="24/7 amazing services"][/site-features]'
                    ) .
                    Html::tag('div', '[product-collections title="Exclusive Products"][/product-collections]') .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Top Categories"][/featured-product-categories]'
                    ) .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag('div', '[theme-ads ads_1="IZ6WU8KUALYF"][/theme-ads]') .
                    Html::tag('div', '[featured-products title="Featured products"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Featured Brands"][/featured-brands]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Visit Our Blog"][/featured-news]'
                    )
                ,
                'template'     => 'homepage',
                'header_style' => 'header-style-4',
            ],
            [
                'name'     => 'Blog',
                'content'  => Html::tag('p', '---'),
                'template' => 'blog-right-sidebar',
            ],
            [
                'name'    => 'Contact',
                'content' => Html::tag('p', '[google-map]502 New Street, Brighton VIC, Australia[/google-map]') .
                    Html::tag('p', '[our-offices][/our-offices]') .
                    Html::tag('p', '[contact-form][/contact-form]'),
            ],
            [
                'name'    => 'About us',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500))
                ,
            ],
            [
                'name'    => 'Cookie Policy',
                'content' => Html::tag('h3', 'EU Cookie Consent') .
                    Html::tag(
                        'p',
                        'To use this website we are using Cookies and collecting some data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.'
                    ) .
                    Html::tag('h4', 'Essential Data') .
                    Html::tag(
                        'p',
                        'The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.'
                    ) .
                    Html::tag(
                        'p',
                        '- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.'
                    ) .
                    Html::tag(
                        'p',
                        '- XSRF-Token Cookie: Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.'
                    ),
            ],
            [
                'name'    => 'Terms & Conditions',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'    => 'Returns & Exchanges',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'    => 'Shipping & Delivery',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'    => 'Privacy Policy',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'     => 'Blog Left Sidebar',
                'content'  => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
                'template' => 'blog-left-sidebar',
            ],
            [
                'name'    => 'FAQ',
                'content' => Html::tag('div', '[faqs][/faqs]'),
            ],
        ];

        Page::truncate();
        PageTranslation::truncate();
        Slug::where('reference_type', Page::class)->delete();
        MetaBoxModel::where('reference_type', Page::class)->delete();
        LanguageMeta::where('reference_type', Page::class)->delete();

        foreach ($pages as $item) {
            $item['user_id'] = 1;

            if (!isset($item['template'])) {
                $item['template'] = 'default';
            }

            $page = Page::create(Arr::except(
                $item,
                ['header_style', 'expanding_product_categories_on_the_homepage']
            ));

            $headerStyle = isset($item['header_style']) ? $item['header_style'] : null;
            if ($headerStyle) {
                MetaBox::saveMetaBoxData($page, 'header_style', $headerStyle);
            }

            if (isset($item['expanding_product_categories_on_the_homepage'])) {
                MetaBox::saveMetaBoxData(
                    $page,
                    'expanding_product_categories_on_the_homepage',
                    $item['expanding_product_categories_on_the_homepage']
                );
            }

            Slug::create([
                'reference_type' => Page::class,
                'reference_id'   => $page->id,
                'key'            => Str::slug($page->name),
                'prefix'         => SlugHelper::getPrefix(Page::class),
            ]);
        }

        $translations = [
            [
                'name'     => 'Trang chủ',
                'content'  =>
                    Html::tag('div', '[simple-slider key="slider-trang-chu-1" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]') .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Miễn phí vận chuyển" subtitle1="Cho đơn hàng từ $50" icon2="general/icon-purchase.png" title2="Miễn phí đổi trả" subtitle2="Trong vòng 30 ngày" icon3="general/icon-bag.png" title3="Giảm 20% mỗi 1 sản phẩm" subtitle3="Khi bạn đăng ký thành viên" icon4="general/icon-operator.png" title4="Hỗ trợ" subtitle4="24/7 dịch vụ tuyệt vời"][/site-features]'
                    ) .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Danh mục nổi bật"][/featured-product-categories]'
                    ) .
                    Html::tag('div', '[product-collections title="Sản phẩm độc quyền"][/product-collections]') .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag('div', '[featured-products title="Sản phẩm nổi bật"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Thương hiệu nổi bật"][/featured-brands]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Tin tức mới nhất"][/featured-news]'
                    )
                ,
            ],
            [
                'name'                                         => 'Trang chủ 2',
                'content'                                      =>
                    Html::tag('div', '[simple-slider key="slider-trang-chu-2" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]') .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag('div', '[product-collections title="Sản phẩm độc quyền"][/product-collections]') .
                    Html::tag('div', '[theme-ads ads_1="IZ6WU8KUALYF"][/theme-ads]') .
                    Html::tag('div', '[featured-products title="Sản phẩm nổi bật"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Thương hiệu nổi bật"][/featured-brands]'
                    ) .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Danh mục nổi bật"][/featured-product-categories]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Tin tức mới nhất"][/featured-news]'
                    ) .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Miễn phí vận chuyển" subtitle1="Cho đơn hàng từ $50" icon2="general/icon-purchase.png" title2="Miễn phí đổi trả" subtitle2="Trong vòng 30 ngày" icon3="general/icon-bag.png" title3="Giảm 20% mỗi 1 sản phẩm" subtitle3="Khi bạn đăng ký thành viên" icon4="general/icon-operator.png" title4="Hỗ trợ" subtitle4="24/7 dịch vụ tuyệt vời"][/site-features]'
                    )
                ,
            ],
            [
                'name'         => 'Trang chủ 3',
                'content'      =>
                    Html::tag(
                        'div',
                        '[simple-slider key="slider-trang-chu-3" ads_1="ILSDKVYFGXPJ" ads_2="IZ6WU8KUALYE" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]'
                    ) .
                    Html::tag('div', '[product-collections title="Sản phẩm độc quyền"][/product-collections]') .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Miễn phí vận chuyển" subtitle1="Cho đơn hàng từ $50" icon2="general/icon-purchase.png" title2="Miễn phí đổi trả" subtitle2="Trong vòng 30 ngày" icon3="general/icon-bag.png" title3="Giảm 20% mỗi 1 sản phẩm" subtitle3="Khi bạn đăng ký thành viên" icon4="general/icon-operator.png" title4="Hỗ trợ" subtitle4="24/7 dịch vụ tuyệt vời"][/site-features]'
                    ) .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Danh mục nổi bật"][/featured-product-categories]'
                    ) .
                    Html::tag('div', '[featured-products title="Sản phẩm nổi bật"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag('div', '[theme-ads ads_1="IZ6WU8KUALYF"][/theme-ads]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Thương hiệu nổi bật"][/featured-brands]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Tin tức mới nhất"][/featured-news]'
                    )
                ,
            ],
            [
                'name'         => 'Trang chủ 4',
                'content'      =>
                    Html::tag('div', '[simple-slider key="slider-trang-chu-4" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]') .
                    Html::tag(
                        'div',
                        '[site-features icon1="general/icon-truck.png" title1="Miễn phí vận chuyển" subtitle1="Cho đơn hàng từ $50" icon2="general/icon-purchase.png" title2="Miễn phí đổi trả" subtitle2="Trong vòng 30 ngày" icon3="general/icon-bag.png" title3="Giảm 20% mỗi 1 sản phẩm" subtitle3="Khi bạn đăng ký thành viên" icon4="general/icon-operator.png" title4="Hỗ trợ" subtitle4="24/7 dịch vụ tuyệt vời"][/site-features]'
                    ) .
                    Html::tag('div', '[product-collections title="Sản phẩm độc quyền"][/product-collections]') .
                    Html::tag(
                        'div',
                        '[featured-product-categories title="Danh mục nổi bật"][/featured-product-categories]'
                    ) .
                    Html::tag(
                        'div',
                        '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
                    ) .
                    Html::tag('div', '[theme-ads ads_1="IZ6WU8KUALYF"][/theme-ads]') .
                    Html::tag('div', '[featured-products title="Sản phẩm nổi bật"][/featured-products]') .
                    Html::tag('div', '[flash-sale show_popup="yes"][/flash-sale]') .
                    Html::tag(
                        'div',
                        '[featured-brands title="Thương hiệu nổi bật"][/featured-brands]'
                    ) .
                    Html::tag('div', '[product-category-products category_id="17"][/product-category-products]') .
                    Html::tag(
                        'div',
                        '[featured-news title="Tin tức mới nhất"][/featured-news]'
                    )
                ,
            ],
            [
                'name'     => 'Tin tức',
                'content'  => Html::tag('p', '---'),
            ],
            [
                'name'    => 'Liên hệ',
                'content' => Html::tag('p', '[google-map]502 New Street, Brighton VIC, Australia[/google-map]') .
                    Html::tag('p', '[our-offices][/our-offices]') .
                    Html::tag('p', '[contact-form][/contact-form]'),
            ],
            [
                'name'    => 'Về chúng tôi',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500))
                ,
            ],
            [
                'name'    => 'Chính sách cookie',
                'content' => Html::tag('h3', 'EU Cookie Consent') .
                    Html::tag(
                        'p',
                        'To use this website we are using Cookies and collecting some data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.'
                    ) .
                    Html::tag('h4', 'Essential Data') .
                    Html::tag(
                        'p',
                        'The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.'
                    ) .
                    Html::tag(
                        'p',
                        '- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.'
                    ) .
                    Html::tag(
                        'p',
                        '- XSRF-Token Cookie: Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.'
                    ),
            ],
            [
                'name'    => 'Điều kiện và điều khoản',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'    => 'Chính sách trả hàng',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'    => 'Chính sách vận chuyển',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'    => 'Chính sách bảo mật',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name'     => 'Blog Sidebar Trái',
                'content'  => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
            ],
            [
                'name'    => 'Câu hỏi thường gặp',
                'content' => Html::tag('div', '[faqs][/faqs]'),
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['pages_id'] = $index + 1;

            PageTranslation::insert($item);
        }
    }
}
