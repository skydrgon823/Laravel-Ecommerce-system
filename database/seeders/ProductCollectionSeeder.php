<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\ProductCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCollectionSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCollection::truncate();

        $productCollections = [
            [
                'name' => 'New Arrival',
            ],
            [
                'name' => 'Best Sellers',
            ],
            [
                'name' => 'Special Offer',
            ],
        ];

        ProductCollection::truncate();

        foreach ($productCollections as $item) {
            $item['slug'] = Str::slug($item['name']);

            ProductCollection::create($item);
        }

        DB::table('ec_product_collections_translations')->truncate();

        $translations = [
            [
                'name' => 'Hàng mới về',
            ],
            [
                'name' => 'Bán chạy nhất',
            ],
            [
                'name' => 'Khuyến mãi đặc biệt',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ec_product_collections_id'] = $index + 1;

            DB::table('ec_product_collections_translations')->insert($item);
        }
    }
}
