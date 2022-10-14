<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\ProductAttribute;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductAttributeSet::truncate();

        ProductAttributeSet::create([
            'title'                     => 'Color',
            'slug'                      => 'color',
            'display_layout'            => 'visual',
            'is_searchable'             => true,
            'is_use_in_product_listing' => true,
            'order'                     => 0,
        ]);

        ProductAttributeSet::create([
            'title'                     => 'Size',
            'slug'                      => 'size',
            'display_layout'            => 'text',
            'is_searchable'             => true,
            'is_use_in_product_listing' => true,
            'order'                     => 1,
        ]);

        ProductAttribute::truncate();

        $productAttributes = [
            [
                'attribute_set_id' => 1,
                'title'            => 'Green',
                'slug'             => 'green',
                'color'            => '#5FB7D4',
                'is_default'       => true,
                'order'            => 1,
            ],
            [
                'attribute_set_id' => 1,
                'title'            => 'Blue',
                'slug'             => 'blue',
                'color'            => '#333333',
                'is_default'       => false,
                'order'            => 2,
            ],
            [
                'attribute_set_id' => 1,
                'title'            => 'Red',
                'slug'             => 'red',
                'color'            => '#DA323F',
                'is_default'       => false,
                'order'            => 3,
            ],
            [
                'attribute_set_id' => 1,
                'title'            => 'Black',
                'slug'             => 'back',
                'color'            => '#2F366C',
                'is_default'       => false,
                'order'            => 4,
            ],
            [
                'attribute_set_id' => 1,
                'title'            => 'Brown',
                'slug'             => 'brown',
                'color'            => '#87554B',
                'is_default'       => false,
                'order'            => 5,
            ],
            [
                'attribute_set_id' => 2,
                'title'            => 'S',
                'slug'             => 's',
                'is_default'       => true,
                'order'            => 1,
            ],
            [
                'attribute_set_id' => 2,
                'title'            => 'M',
                'slug'             => 'm',
                'is_default'       => false,
                'order'            => 2,
            ],
            [
                'attribute_set_id' => 2,
                'title'            => 'L',
                'slug'             => 'l',
                'is_default'       => false,
                'order'            => 3,
            ],
            [
                'attribute_set_id' => 2,
                'title'            => 'XL',
                'slug'             => 'xl',
                'is_default'       => false,
                'order'            => 4,
            ],
            [
                'attribute_set_id' => 2,
                'title'            => 'XXL',
                'slug'             => 'xxl',
                'is_default'       => false,
                'order'            => 5,
            ],
        ];

        foreach ($productAttributes as $item) {
            ProductAttribute::create($item);
        }

        if (is_plugin_active('language')) {
            DB::table('ec_product_attributes_translations')->truncate();
            DB::table('ec_product_attribute_sets_translations')->truncate();

            DB::table('ec_product_attribute_sets_translations')->insert([
                'title'                        => 'Màu sắc',
                'ec_product_attribute_sets_id' => 1,
                'lang_code'                    => 'vi',
            ]);

            DB::table('ec_product_attribute_sets_translations')->insert([
                'title'                        => 'Kích thước',
                'ec_product_attribute_sets_id' => 2,
                'lang_code'                    => 'vi',
            ]);

            $translations = [
                [
                    'title' => 'Xanh lá cây',
                ],
                [
                    'title' => 'Xanh da trời',
                ],
                [
                    'title' => 'Đỏ',
                ],
                [
                    'title' => 'Đen',
                ],
                [
                    'title' => 'Nâu',
                ],
                [
                    'title' => 'S',
                ],
                [
                    'title' => 'M',
                ],
                [
                    'title' => 'L',
                ],
                [
                    'title' => 'XL',
                ],
                [
                    'title' => 'XXL',
                ],
            ];

            foreach ($translations as $index => $item) {
                $item['lang_code'] = 'vi';
                $item['ec_product_attributes_id'] = $index + 1;

                DB::table('ec_product_attributes_translations')->insert($item);
            }
        }
    }
}
