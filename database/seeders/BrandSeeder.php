<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\BrandTranslation;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Str;
use SlugHelper;

class BrandSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('brands');

        $brands = [
            [
                'name' => 'Perxsion',
            ],
            [
                'name' => 'Hiching',
            ],
            [
                'name' => 'Kepslo',
            ],
            [
                'name' => 'Groneba',
            ],
            [
                'name' => 'Babian',
            ],
            [
                'name' => 'Valorant',
            ],
            [
                'name' => 'Pure',
            ],
        ];

        Brand::truncate();
        BrandTranslation::truncate();
        Slug::where('reference_type', Brand::class)->delete();

        foreach ($brands as $key => $item) {
            $item['order'] = $key;
            $item['is_featured'] = true;
            $item['logo'] = 'brands/' . ($key + 1) . '.png';
            $brand = Brand::create($item);

            Slug::create([
                'reference_type' => Brand::class,
                'reference_id'   => $brand->id,
                'key'            => Str::slug($brand->name),
                'prefix'         => SlugHelper::getPrefix(Brand::class),
            ]);
        }

        $translations = [
            [
                'name' => 'Perxsion',
            ],
            [
                'name' => 'Hiching',
            ],
            [
                'name' => 'Kepslo',
            ],
            [
                'name' => 'Groneba',
            ],
            [
                'name' => 'Babian',
            ],
            [
                'name' => 'Valorant',
            ],
            [
                'name' => 'Pure',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ec_brands_id'] = $index + 1;

            BrandTranslation::insert($item);
        }
    }
}
