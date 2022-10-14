<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\ProductTag;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Str;
use SlugHelper;

class ProductTagSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            [
                'name' => 'Wallet',
            ],
            [
                'name' => 'Bags',
            ],
            [
                'name' => 'Shoes',
            ],
            [
                'name' => 'Clothes',
            ],
            [
                'name' => 'Hand bag',
            ],
        ];

        ProductTag::truncate();
        Slug::where('reference_type', ProductTag::class)->delete();

        foreach ($tags as $item) {
            $tag = ProductTag::create($item);

            Slug::create([
                'reference_type' => ProductTag::class,
                'reference_id'   => $tag->id,
                'key'            => Str::slug($tag->name),
                'prefix'         => SlugHelper::getPrefix(ProductTag::class),
            ]);
        }
    }
}
