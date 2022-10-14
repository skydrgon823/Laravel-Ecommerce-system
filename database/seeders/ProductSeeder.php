<?php

namespace Database\Seeders;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductFile;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Models\Shipment;
use Botble\Ecommerce\Models\ShipmentHistory;
use Botble\Ecommerce\Models\Wishlist;
use Botble\Ecommerce\Services\Products\StoreProductService;
use Botble\Payment\Models\Payment;
use Botble\Slug\Models\Slug;
use Faker\Factory;
use File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MetaBox;
use SlugHelper;

class ProductSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('products');

        $faker = Factory::create();

        $products = [
            [
                'name'        => 'Smart Home Speaker',
                'price'       => $faker->numberBetween(200, 500),
                'is_featured' => true,
                'layout'      => 'product-right-sidebar',
            ],
            [
                'name'        => 'Headphone Ultra Bass',
                'price'       => $faker->numberBetween(200, 500),
                'sale_price'  => $faker->numberBetween(150, 200),
                'is_featured' => true,
                'layout'      => 'product-left-sidebar',
            ],
            [
                'name'        => 'Boxed - Bluetooth Headphone',
                'price'       => $faker->numberBetween(200, 500),
                'sale_price'  => $faker->numberBetween(50, 60),
                'is_featured' => true,
                'layout'      => 'product-full-width',
            ],
            [
                'name'        => 'Chikie - Bluetooth Speaker',
                'price'       => $faker->numberBetween(70, 90),
                'is_featured' => true,
            ],
            [
                'name'        => 'Camera Hikvision HK-35VS8',
                'price'       => $faker->numberBetween(40, 50),
                'sale_price'  => $faker->numberBetween(50, 60),
                'is_featured' => true,
            ],
            [
                'name'        => 'Camera Samsung SS-24',
                'price'       => $faker->numberBetween(50, 60),
                'is_featured' => true,
            ],
            [
                'name'        => 'Leather Watch Band',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Apple iPhone 13 Plus',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Macbook Pro 2015',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Macbook Air 12 inch',
                'price'       => $faker->numberBetween(200, 500),
                'sale_price'  => $faker->numberBetween(150, 200),
                'is_featured' => true,
            ],
            [
                'name'        => 'Apple Watch Serial 7',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Macbook Pro 13 inch',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Apple Keyboard',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'MacSafe 80W',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Hand playstation',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Apple Airpods Serial 3',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Cool Smart Watches',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Black Smart Watches',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Leather Watch Band Serial 3',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Macbook Pro 2015 13 inch',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Historic Alarm Clock',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Black Glasses',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Phillips Mouse',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
            [
                'name'        => 'Gaming Keyboard',
                'price'       => $faker->numberBetween(110, 130),
                'sale_price'  => $faker->numberBetween(90, 100),
                'is_featured' => true,
            ],
        ];

        Product::truncate();
        DB::table('ec_product_with_attribute_set')->truncate();
        DB::table('ec_product_variations')->truncate();
        DB::table('ec_product_variation_items')->truncate();
        DB::table('ec_product_collection_products')->truncate();
        DB::table('ec_product_label_products')->truncate();
        DB::table('ec_product_category_product')->truncate();
        DB::table('ec_product_related_relations')->truncate();
        Slug::where('reference_type', Product::class)->delete();
        Wishlist::truncate();
        Order::truncate();
        OrderAddress::truncate();
        OrderProduct::truncate();
        OrderHistory::truncate();
        Shipment::truncate();
        ShipmentHistory::truncate();
        Payment::truncate();

        MetaBoxModel::where('reference_type', Product::class)->delete();
        ProductFile::truncate();

        foreach ($products as $key => $item) {
            if (!isset($item['description'])) {
                $item['description'] = '<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline.</p>
                    <ul style="padding-left: 0;">
                        <li style="list-style: none; margin-bottom: 10px"><i class="far fa-hourglass mr-5 text-brand"></i> 1 Year AL Jazeera Brand Warranty</li>
                        <li style="list-style: none; margin-bottom: 10px"><i class="far fa-paper-plane mr-5 text-brand"></i> 30 Day Return Policy</li>
                        <li style="list-style: none;"><i class="far fa-address-card mr-5 text-brand"></i> Cash on Delivery available</li>
                    </ul>';
            }

            $item['content'] = '<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains&rsquo; signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>
                                <p>- Casual unisex fit</p>

                                <p>- 64% polyester, 36% polyurethane</p>

                                <p>- Water column pressure: 4000 mm</p>

                                <p>- Model is 187cm tall and wearing a size S / M</p>

                                <p>- Unisex fit</p>

                                <p>- Drawstring hood with built-in cap</p>

                                <p>- Front placket with snap buttons</p>

                                <p>- Ventilation under armpit</p>

                                <p>- Adjustable cuffs</p>

                                <p>- Double welted front pockets</p>

                                <p>- Adjustable elastic string at hempen</p>

                                <p>- Ultrasonically welded seams</p>

                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>';
            $item['status'] = BaseStatusEnum::PUBLISHED;
            $item['sku'] = 'HS-' . $faker->numberBetween(100, 200);
            $item['brand_id'] = $faker->numberBetween(1, 7);
            $item['tax_id'] = 1;
            $item['views'] = $faker->numberBetween(1000, 200000);
            $item['quantity'] = $faker->numberBetween(10, 20);
            $item['length'] = $faker->numberBetween(10, 20);
            $item['wide'] = $faker->numberBetween(10, 20);
            $item['height'] = $faker->numberBetween(10, 20);
            $item['weight'] = $faker->numberBetween(500, 900);
            $item['with_storehouse_management'] = true;

            // Support Digital Product
            $productName = $item['name'];
            if ($key % 4 == 0) {
                $item['product_type'] = ProductTypeEnum::DIGITAL;
                $item['name'] .= ' (' . ProductTypeEnum::DIGITAL()->label() . ')';
            }

            $images = [
                'products/' . ($key + 1) . '.jpg',
            ];

            for ($i = 1; $i <= 10; $i++) {
                if (File::exists(database_path('seeders/files/products/' . ($key + 1) . '-' . $i . '.jpg'))) {
                    $images[] = 'products/' . ($key + 1) . '-' . $i . '.jpg';
                }
            }

            $item['images'] = json_encode($images);

            $product = Product::create(Arr::except($item, ['layout']));

            $layout = $item['layout'] ?? null;
            if ($layout) {
                MetaBox::saveMetaBoxData($product, 'layout', $layout);
            }

            $product->productCollections()->sync([$faker->numberBetween(1, 3)]);

            if ($product->id % 7 == 0) {
                $product->productLabels()->sync([$faker->numberBetween(1, 3)]);
            }

            $product->categories()->sync([
                $faker->numberBetween(1, 37),
            ]);

            $product->tags()->sync([
                $faker->numberBetween(1, 6),
                $faker->numberBetween(1, 6),
                $faker->numberBetween(1, 6),
            ]);

            Slug::create([
                'reference_type' => Product::class,
                'reference_id'   => $product->id,
                'key'            => Str::slug($productName),
                'prefix'         => SlugHelper::getPrefix(Product::class),
            ]);

            MetaBox::saveMetaBoxData(
                $product,
                'faq_schema_config',
                json_decode(
                    '[[{"key":"question","value":"What Shipping Methods Are Available?"},{"key":"answer","value":"Ex Portland Pitchfork irure mustache. Eutra fap before they sold out literally. Aliquip ugh bicycle rights actually mlkshk, seitan squid craft beer tempor."}],[{"key":"question","value":"Do You Ship Internationally?"},{"key":"answer","value":"Hoodie tote bag mixtape tofu. Typewriter jean shorts wolf quinoa, messenger bag organic freegan cray."}],[{"key":"question","value":"How Long Will It Take To Get My Package?"},{"key":"answer","value":"Swag slow-carb quinoa VHS typewriter pork belly brunch, paleo single-origin coffee Wes Anderson. Flexitarian Pitchfork forage, literally paleo fap pour-over. Wes Anderson Pinterest YOLO fanny pack meggings, deep v XOXO chambray sustainable slow-carb raw denim church-key fap chillwave Etsy. +1 typewriter kitsch, American Apparel tofu Banksy Vice."}],[{"key":"question","value":"What Payment Methods Are Accepted?"},{"key":"answer","value":"Fashion axe DIY jean shorts, swag kale chips meh polaroid kogi butcher Wes Anderson chambray next level semiotics gentrify yr. Voluptate photo booth fugiat Vice. Austin sed Williamsburg, ea labore raw denim voluptate cred proident mixtape excepteur mustache. Twee chia photo booth readymade food truck, hoodie roof party swag keytar PBR DIY."}],[{"key":"question","value":"Is Buying On-Line Safe?"},{"key":"answer","value":"Art party authentic freegan semiotics jean shorts chia cred. Neutra Austin roof party Brooklyn, synth Thundercats swag 8-bit photo booth. Plaid letterpress leggings craft beer meh ethical Pinterest."}]]',
                    true
                )
            );
        }

        $countProducts = count($products);

        foreach ($products as $key => $item) {
            $product = Product::find($key + 1);

            if (!$product) {
                continue;
            }

            $product->productAttributeSets()->sync([1, 2]);

            $product->crossSales()->sync([
                $this->random(1, $countProducts, [$product->id]),
                $this->random(1, $countProducts, [$product->id]),
                $this->random(1, $countProducts, [$product->id]),
                $this->random(1, $countProducts, [$product->id]),
            ]);

            for ($j = 0; $j < $faker->numberBetween(1, 5); $j++) {
                $variation = Product::create([
                    'name'                       => $product->name,
                    'status'                     => BaseStatusEnum::PUBLISHED,
                    'sku'                        => $product->sku . '-A' . $j,
                    'quantity'                   => $product->quantity,
                    'weight'                     => $product->weight,
                    'height'                     => $product->height,
                    'wide'                       => $product->wide,
                    'length'                     => $product->length,
                    'price'                      => $product->price,
                    'sale_price'                 => $product->id % 4 == 0 ? ($product->price - $product->price * $faker->numberBetween(
                        10,
                        30
                    ) / 100) : null,
                    'brand_id'                   => $product->brand_id,
                    'with_storehouse_management' => $product->with_storehouse_management,
                    'is_variation'               => true,
                    'images'                     => json_encode([$product->images[$j] ?? Arr::first($product->images)]),
                    'product_type'               => $product->product_type,
                ]);

                $productVariation = ProductVariation::create([
                    'product_id'              => $variation->id,
                    'configurable_product_id' => $product->id,
                    'is_default'              => $j == 0,
                ]);

                if ($productVariation->is_default) {
                    $product->update([
                        'sku'        => $variation->sku,
                        'sale_price' => $variation->sale_price,
                    ]);
                }

                ProductVariationItem::create([
                    'attribute_id' => $faker->numberBetween(1, 5),
                    'variation_id' => $productVariation->id,
                ]);

                ProductVariationItem::create([
                    'attribute_id' => $faker->numberBetween(6, 10),
                    'variation_id' => $productVariation->id,
                ]);

                if ($product->isTypeDigital()) {
                    foreach ($product->images as $img) {
                        $productFile = database_path('seeders/files/' . $img);

                        if (!File::isFile($productFile)) {
                            continue;
                        }

                        $fileUpload = new UploadedFile($productFile, Str::replace('products/', '', $img), 'image/jpeg', null, true);
                        $productFileData = app(StoreProductService::class)->saveProductFile($fileUpload);
                        $variation->productFiles()->create($productFileData);
                    }
                }
            }
        }

        DB::table('ec_products_translations')->truncate();

        $translations = [
            [
                'name' => 'Loa thông minh',
            ],
            [
                'name' => 'Tai nghe Ultra Bass',
            ],
            [
                'name' => 'Tai nghe Bluetooth',
            ],
            [
                'name' => 'Chikie - Loa Bluetooth',
            ],
            [
                'name' => 'Camera Hikvision HK-35VS8',
            ],
            [
                'name' => 'Camera Samsung SS-24',
            ],
            [
                'name' => 'Dây đeo đồng hồ da',
            ],
            [
                'name' => 'Điện thoại Apple iPhone 13 Plus',
            ],
            [
                'name' => 'Máy tính Macbook Pro 2015',
            ],
            [
                'name' => 'Máy tính Macbook Air 12 inch',
            ],
            [
                'name' => 'Đồng hồ Apple Serial 7',
            ],
            [
                'name' => 'Máy tính Macbook Pro 13 inch',
            ],
            [
                'name' => 'Bàn phím Apple',
            ],
            [
                'name' => 'Cục sạc MacSafe 80W',
            ],
            [
                'name' => 'Tay cầm chơi game',
            ],
            [
                'name' => 'Apple Airpods Serial 3',
            ],
            [
                'name' => 'Cool Smart Watches',
            ],
            [
                'name' => 'Black Smart Watches',
            ],
            [
                'name' => 'Leather Watch Band Serial 3',
            ],
            [
                'name' => 'Macbook Pro 2015 13 inch',
            ],
            [
                'name' => 'Đồng hồ báo thức cổ điển',
            ],
            [
                'name' => 'Kính đen cool ngầu',
            ],
            [
                'name' => 'Chuột máy tính Phillips',
            ],
            [
                'name' => 'Bàn phím chơi game',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ec_products_id'] = $index + 1;

            DB::table('ec_products_translations')->insert($item);

            $product = Product::find($index + 1);
            if ($product) {
                $variations = $product->variations()->get();

                foreach ($variations as $variation) {
                    $item['lang_code'] = 'vi';
                    $item['ec_products_id'] = $variation->product->id;

                    DB::table('ec_products_translations')->insert($item);
                }
            }
        }
    }
}
