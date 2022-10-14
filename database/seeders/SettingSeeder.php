<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Setting\Models\Setting;
use Botble\Slug\Models\Slug;
use SlugHelper;

class SettingSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key'   => 'media_random_hash',
                'value' => md5(time()),
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Post::class),
                'value' => 'blog',
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Category::class),
                'value' => 'blog',
            ],
            [
                'key'   => 'payment_cod_status',
                'value' => 1,
            ],
            [
                'key'   => 'payment_cod_description',
                'value' => 'Please pay money directly to the postman, if you choose cash on delivery method (COD).',
            ],
            [
                'key'   => 'payment_bank_transfer_status',
                'value' => 1,
            ],
            [
                'key'   => 'payment_bank_transfer_description',
                'value' => 'Please send money to our bank account: ACB - 69270 213 19.',
            ],
            [
                'key'   => 'plugins_ecommerce_customer_new_order_status',
                'value' => '0',
            ],
            [
                'key'   => 'plugins_ecommerce_admin_new_order_status',
                'value' => '0',
            ],
            [
                'key'   => 'ecommerce_load_countries_states_cities_from_location_plugin',
                'value' => '0',
            ],
            [
                'key'   => 'payment_stripe_payment_type',
                'value' => 'stripe_checkout',
            ],
            [
                'key'   => 'simple_slider_using_assets',
                'value' => 0,
            ],
            [
                'key'   => 'sale_popup_display_pages',
                'value' => json_encode(['public.index']),
            ],
            [
                'key'   => 'ecommerce_is_enabled_support_digital_products',
                'value' => '1',
            ],
        ];

        Setting::whereIn('key', collect($settings)->pluck('key')->all())->delete();

        Setting::insert($settings);

        Slug::where('reference_type', Post::class)->update(['prefix' => 'blog']);
        Slug::where('reference_type', Category::class)->update(['prefix' => 'blog']);
    }
}
