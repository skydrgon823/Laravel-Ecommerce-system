<?php

namespace Database\Seeders;

use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MetaBox;
use SlugHelper;

class ProductCategorySeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('product-categories');

        $categories = [
            [
                'name' => 'Hot Promotions',
                'icon' => 'far fa-star',
            ],
            [
                'name'        => 'Electronics',
                'icon'        => 'wowy-font-cpu',
                'image'       => 'product-categories/1.jpg',
                'is_featured' => true,
                'children'    => [
                    [
                        'name' => 'Home Audio & Theaters',
                    ],
                    [
                        'name' => 'TV & Videos',
                    ],
                    [
                        'name' => 'Camera, Photos & Videos',
                    ],
                    [
                        'name' => 'Cellphones & Accessories',
                    ],
                    [
                        'name' => 'Headphones',
                    ],
                    [
                        'name' => 'Videos games',
                    ],
                    [
                        'name' => 'Wireless Speakers',
                    ],
                    [
                        'name' => 'Office Electronic',
                    ],
                ],
            ],
            [
                'name'        => 'Clothing',
                'icon'        => 'wowy-font-tshirt',
                'image'       => 'product-categories/2.jpg',
                'is_featured' => true,
            ],
            [
                'name'        => 'Computers',
                'icon'        => 'wowy-font-desktop',
                'image'       => 'product-categories/3.jpg',
                'is_featured' => true,
                'children'    => [
                    [
                        'name' => 'Computer & Tablets',
                    ],
                    [
                        'name' => 'Laptop',
                    ],
                    [
                        'name' => 'Monitors',
                    ],
                    [
                        'name' => 'Computer Components',
                    ],
                ],
            ],
            [
                'name'        => 'Home & Kitchen',
                'icon'        => 'wowy-font-home',
                'image'       => 'product-categories/4.jpg',
                'is_featured' => true,
            ],
            [
                'name'        => 'Health & Beauty',
                'icon'        => 'wowy-font-dress',
                'image'       => 'product-categories/5.jpg',
                'is_featured' => true,
            ],
            [
                'name'        => 'Jewelry & Watch',
                'icon'        => 'wowy-font-diamond',
                'image'       => 'product-categories/6.jpg',
                'is_featured' => true,
            ],
            [
                'name'        => 'Technology Toys',
                'icon'        => 'far fa-microchip',
                'image'       => 'product-categories/7.jpg',
                'is_featured' => true,
                'children'    => [
                    [
                        'name' => 'Drive & Storages',
                    ],
                    [
                        'name' => 'Gaming Laptop',
                    ],
                    [
                        'name' => 'Security & Protection',
                    ],
                    [
                        'name' => 'Accessories',
                    ],
                ],
            ],
            [
                'name'        => 'Phones',
                'icon'        => 'wowy-font-smartphone',
                'image'       => 'product-categories/8.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'Babies & Moms',
                'icon' => 'wowy-font-teddy-bear',
            ],
            [
                'name' => 'Sport & Outdoor',
                'icon' => 'wowy-font-kite',
            ],
            [
                'name' => 'Books & Office',
                'icon' => 'far fa-book',
            ],
            [
                'name' => 'Cars & Motorcycles',
                'icon' => 'far fa-car',
            ],
            [
                'name' => 'Home Improvements',
                'icon' => 'wowy-font-home',
            ],
        ];

        ProductCategory::truncate();
        Slug::where('reference_type', ProductCategory::class)->delete();
        MetaBoxModel::where('reference_type', ProductCategory::class)->delete();

        foreach ($categories as $index => $item) {
            $this->createCategoryItem($index, $item);
        }

        // Translations
        DB::table('ec_product_categories_translations')->truncate();

        $translations = [
            [
                'name' => 'Khuyến mãi nổi bật',
            ],
            [
                'name'     => 'Điện tử',
                'children' => [
                    [
                        'name' => 'Âm thanh và hình ảnh',
                    ],
                    [
                        'name' => 'TV & Videos',
                    ],
                    [
                        'name' => 'Máy ảnh, Ảnh & Videos',
                    ],
                    [
                        'name' => 'Điện thoại & Phụ kiện',
                    ],
                    [
                        'name' => 'Tai nghe',
                    ],
                    [
                        'name' => 'Trò chơi',
                    ],
                    [
                        'name' => 'Tai nghe không dây',
                    ],
                    [
                        'name' => 'Điện tử văn phòng',
                    ],
                ],
            ],
            [
                'name' => 'Quần áo',
            ],
            [
                'name'     => 'Máy tính',
                'children' => [
                    [
                        'name' => 'Máy tính và máy tính bảng',
                    ],
                    [
                        'name' => 'Máy vi tính',
                    ],
                    [
                        'name' => 'Màn hình',
                    ],
                    [
                        'name' => 'Thiết bị máy tính',
                    ],
                ],
            ],
            [
                'name' => 'Đồ dùng nhà bếp',
            ],
            [
                'name' => 'Sức khỏe & làm đẹp',
            ],
            [
                'name' => 'Đồng hồ & trang sức',
            ],
            [
                'name'     => 'Đồ chơi công nghệ',
                'children' => [
                    [
                        'name' => 'Thiết bị lưu trữ',
                    ],
                    [
                        'name' => 'Máy tính chơi game',
                    ],
                    [
                        'name' => 'Bảo mật',
                    ],
                    [
                        'name' => 'Phụ kiện',
                    ],
                ],
            ],
            [
                'name' => 'Điện thoại',
            ],
            [
                'name' => 'Mẹ và bé',
            ],
            [
                'name' => 'Thể thao & ngoài trời',
            ],
            [
                'name' => 'Sách & Văn phòng',
            ],
            [
                'name' => 'Ôto & Xe máy',
            ],
            [
                'name' => 'Thiết bị gia đình',
            ],
        ];

        $count = 1;
        foreach ($translations as $translation) {
            $translation['lang_code'] = 'vi';
            $translation['ec_product_categories_id'] = $count;

            DB::table('ec_product_categories_translations')->insert(Arr::except($translation, ['children']));

            if (isset($translation['children']) && !empty($translation['children'])) {
                foreach ($translation['children'] as $child) {
                    $child['lang_code'] = 'vi';
                    $child['ec_product_categories_id'] = $count + 1;

                    DB::table('ec_product_categories_translations')->insert($child);

                    $count++;
                }
            }

            $count++;
        }
    }

    /**
     * @param int $index
     * @param array $category
     * @param int $parentId
     */
    protected function createCategoryItem(int $index, array $category, int $parentId = 0): void
    {
        $category['parent_id'] = $parentId;
        $category['order'] = $index;

        if (Arr::has($category, 'children')) {
            $children = $category['children'];
            unset($category['children']);
        } else {
            $children = [];
        }

        $createdCategory = ProductCategory::create(Arr::except($category, ['icon']));

        Slug::create([
            'reference_type' => ProductCategory::class,
            'reference_id'   => $createdCategory->id,
            'key'            => Str::slug($createdCategory->name),
            'prefix'         => SlugHelper::getPrefix(ProductCategory::class),
        ]);

        if (isset($category['icon'])) {
            MetaBox::saveMetaBoxData($createdCategory, 'icon', $category['icon']);
        }

        if ($children) {
            foreach ($children as $childIndex => $child) {
                $this->createCategoryItem($childIndex, $child, $createdCategory->id);
            }
        }
    }
}
