<?php

namespace Database\Seeders;

use Botble\Ecommerce\Models\ProductLabel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductLabel::truncate();

        $productCollections = [
            [
                'name'  => 'Hot',
                'color' => '#ec2434',
            ],
            [
                'name'  => 'New',
                'color' => '#00c9a7',
            ],
            [
                'name'  => 'Sale',
                'color' => '#fe9931',
            ],
        ];

        foreach ($productCollections as $item) {
            ProductLabel::create($item);
        }

        DB::table('ec_product_labels_translations')->truncate();

        $translations = [
            [
                'name'  => 'Nổi bật',
            ],
            [
                'name'  => 'Mới',
            ],
            [
                'name'  => 'Giảm giá',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ec_product_labels_id'] = $index + 1;

            DB::table('ec_product_labels_translations')->insert($item);
        }
    }
}
