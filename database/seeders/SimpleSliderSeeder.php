<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Illuminate\Support\Arr;
use MetaBox;

class SimpleSliderSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('sliders');

        SimpleSlider::truncate();
        SimpleSliderItem::truncate();
        LanguageMeta::where('reference_type', SimpleSlider::class)->delete();

        $sliders = [
            'en_US' => [
                [
                    'name'  => 'Home slider 1',
                    'key'   => 'home-slider-1',
                    'total' => 3,
                    'style' => ''
                ],
                [
                    'name'  => 'Home slider 2',
                    'key'   => 'home-slider-2',
                    'total' => 3,
                    'style' => 'style-2'
                ],
                [
                    'name'  => 'Home slider 3',
                    'key'   => 'home-slider-3',
                    'total' => 2,
                    'style' => 'style-3'
                ],
                [
                    'name'  => 'Home slider 4',
                    'key'   => 'home-slider-4',
                    'total' => 3,
                    'style' => 'style-4'
                ],
            ],
            'vi' => [
                [
                    'name'  => 'Slider trang chủ 1',
                    'key'   => 'slider-trang-chu-1',
                    'total' => 3,
                    'style' => ''
                ],
                [
                    'name'  => 'Slider trang chủ 2',
                    'key'   => 'slider-trang-chu-2',
                    'total' => 3,
                    'style' => 'style-2'
                ],
                [
                    'name'  => 'Slider trang chủ 3',
                    'key'   => 'slider-trang-chu-3',
                    'total' => 2,
                    'style' => 'style-3'
                ],
                [
                    'name'  => 'Slider trang chủ 4',
                    'key'   => 'slider-trang-chu-4',
                    'total' => 3,
                    'style' => 'style-4'
                ],
            ],
        ];

        $sliderItems = [
            'en_US' => [
                [
                    'title'          => 'Super Value Deals',
                    'link'           => '/products',
                    'description'    => 'Save more with coupons & up to 70% off',
                    'button_text'    => 'Shop now',
                    'subtitle'       => 'Trade-In Offer',
                    'highlight_text' => 'On All Products',
                ],
                [
                    'title'          => 'Tech Trending',
                    'link'           => '/products',
                    'description'    => 'Save more with coupons & up to 20% off',
                    'button_text'    => 'Discover now',
                    'subtitle'       => 'Tech Promotions',
                    'highlight_text' => 'Great Collection',
                ],
                [
                    'title'          => 'Big Deals From',
                    'link'           => '/products',
                    'description'    => 'Headphone, Gaming Laptop, PC and more...',
                    'button_text'    => 'Shop now',
                    'subtitle'       => 'Upcoming Offer',
                    'highlight_text' => 'Manufacturer',
                ],
            ],
            'vi' => [
                [
                    'title'          => 'Giảm giá đặc biệt',
                    'link'           => '/products',
                    'description'    => 'Tiết kiệm hơn với mã giảm giá 70%',
                    'button_text'    => 'Mua ngay',
                    'subtitle'       => 'Khuyến mãi',
                    'highlight_text' => 'Tất cả sản phẩm',
                ],
                [
                    'title'          => 'Công nghệ nổi bật',
                    'link'           => '/products',
                    'description'    => 'Tiết kiệm hơn với mã giảm giá 20%',
                    'button_text'    => 'Khám phá ngay',
                    'subtitle'       => 'Khuyến mãi sản phẩm công nghệ',
                    'highlight_text' => 'Bộ sưu tập tốt nhất',
                ],
                [
                    'title'          => 'Giảm giá lớn nhất từ',
                    'link'           => '/products',
                    'description'    => 'Tai nghe, Máy tính chơi game, PC và hơn nữa...',
                    'button_text'    => 'Mua ngay',
                    'subtitle'       => 'Khuyến mãi sắp tới',
                    'highlight_text' => 'Nhà cung cấp',
                ],
            ],
        ];

        foreach ($sliders as $locale => $sliderItem) {
            foreach ($sliderItem as $index => $value) {
                $slider = SimpleSlider::create(Arr::only($value, ['name', 'key']));

                $originValue = null;

                if ($locale !== 'en_US') {
                    $originValue = LanguageMeta::where([
                        'reference_id'   => $index + 1,
                        'reference_type' => SimpleSlider::class,
                    ])->value('lang_meta_origin');
                }

                LanguageMeta::saveMetaData($slider, $locale, $originValue);

                if ($value['style']) {
                    MetaBox::saveMetaBoxData($slider, 'simple_slider_style', $value['style']);
                }

                foreach (collect($sliderItems[$locale])->take($value['total']) as $key => $item) {
                    $item['image'] = 'sliders/' . ($index + 1) . '-' . ($key + 1) . '.png';
                    $item['order'] = $key + 1;
                    $item['simple_slider_id'] = $slider->id;

                    $sliderItem = SimpleSliderItem::create(Arr::except($item, ['button_text', 'subtitle', 'highlight_text']));

                    MetaBox::saveMetaBoxData($sliderItem, 'button_text', $item['button_text']);
                    MetaBox::saveMetaBoxData($sliderItem, 'subtitle', $item['subtitle']);
                    MetaBox::saveMetaBoxData($sliderItem, 'highlight_text', $item['highlight_text']);
                }
            }
        }
    }
}
