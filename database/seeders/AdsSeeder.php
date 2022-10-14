<?php

namespace Database\Seeders;

use Botble\Ads\Models\Ads;
use Botble\Ads\Models\AdsTranslation;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MetaBox;

class AdsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('promotion');

        Ads::truncate();
        AdsTranslation::truncate();

        $items = [
            [
                'name'        => 'Smart Offer',
                'location'    => 'not_set',
                'key'         => 'IZ6WU8KUALYD',
                'subtitle'    => "Save 20% on\niPhone 12",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Sale off',
                'location'    => 'not_set',
                'key'         => 'ILSFJVYFGCPZ',
                'subtitle'    => "Great Camera\nCollection",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'New Arrivals',
                'location'    => 'not_set',
                'key'         => 'ILSDKVYFGXPH',
                'subtitle'    => "Shop Today’s\nDeals & Offers",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Gaming Area',
                'location'    => 'not_set',
                'key'         => 'ILSDKVYFGXPJ',
                'subtitle'    => "Save 17% on\nAssus Laptop",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Smart Offer',
                'location'    => 'not_set',
                'key'         => 'IZ6WU8KUALYE',
                'subtitle'    => "Save 20% on\niPhone 12",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Repair Services',
                'location'    => 'banner-big',
                'key'         => 'IZ6WU8KUALYF',
                'subtitle'    => "We're an Apple\nAuthorised Service Provider",
                'button_text' => 'Learn more',
            ],
        ];

        foreach ($items as $index => $item) {
            $item['order'] = $index + 1;
            if (!isset($item['key'])) {
                $item['key'] = strtoupper(Str::random(12));
            }
            $item['expired_at'] = now()->addYears(5)->toDateString();
            $item['image'] = 'promotion/' . ($index + 1) . '.png';
            $item['url'] = '/products';

            $ad = Ads::create(Arr::except($item, ['subtitle', 'button_text']));

            MetaBox::saveMetaBoxData($ad, 'button_text', $item['button_text']);
            MetaBox::saveMetaBoxData($ad, 'subtitle', $item['subtitle']);
        }

        $translations = [
            [
                'name'        => 'Smart Offer',
                'subtitle'    => "Save 20% on\niPhone 12",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Sale off',
                'subtitle'    => "Great Camera\nCollection",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'New Arrivals',
                'subtitle'    => "Shop Today’s\nDeals & Offers",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Gaming Area',
                'subtitle'    => "Save 17% on\nAssus Laptop",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Smart Offer',
                'subtitle'    => "Save 20% on\niPhone 12",
                'button_text' => 'Shop now',
            ],
            [
                'name'        => 'Repair Services',
                'subtitle'    => "We're an Apple\nAuthorised Service Provider",
                'button_text' => 'Learn more',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ads_id'] = $index + 1;
            $item['image'] = 'promotion/' . ($index + 1) . '.png';
            $item['url'] = '/vi/products';

            AdsTranslation::insert(Arr::except($item, ['subtitle', 'button_text']));

            MetaBox::saveMetaBoxData(Ads::find($index + 1), 'vi_subtitle', $item['subtitle']);
            MetaBox::saveMetaBoxData(Ads::find($index + 1), 'vi_button_text', $item['button_text']);
        }
    }
}
