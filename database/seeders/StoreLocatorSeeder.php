<?php

namespace Database\Seeders;

use Botble\Ecommerce\Models\StoreLocator;
use Botble\Setting\Models\Setting as SettingModel;
use Illuminate\Database\Seeder;

class StoreLocatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreLocator::truncate();

        $storeLocator = StoreLocator::create([
            'name'                 => 'Wowy',
            'email'                => 'sales@botble.com',
            'phone'                => '18006268',
            'address'              => 'North Link Building, 10 Admiralty Street',
            'state'                => 'Singapore',
            'city'                 => 'Singapore',
            'country'              => 'SG',
            'is_primary'           => 1,
            'is_shipping_location' => 1,
        ]);

        SettingModel::whereIn('key', [
            'ecommerce_store_name',
            'ecommerce_store_phone',
            'ecommerce_store_address',
            'ecommerce_store_state',
            'ecommerce_store_city',
            'ecommerce_store_country',
        ])->delete();

        SettingModel::insertOrIgnore([
            [
                'key'   => 'ecommerce_store_name',
                'value' => $storeLocator->name,
            ],
            [
                'key'   => 'ecommerce_store_phone',
                'value' => $storeLocator->phone,
            ],
            [
                'key'   => 'ecommerce_store_address',
                'value' => $storeLocator->address,
            ],
            [
                'key'   => 'ecommerce_store_state',
                'value' => $storeLocator->state,
            ],
            [
                'key'   => 'ecommerce_store_city',
                'value' => $storeLocator->city,
            ],
            [
                'key'   => 'ecommerce_store_country',
                'value' => $storeLocator->country,
            ],
        ]);
    }
}
