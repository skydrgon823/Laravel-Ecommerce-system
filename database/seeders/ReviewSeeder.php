<?php

namespace Database\Seeders;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Review;
use File;

class ReviewSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::deleteDirectory(config('filesystems.disks.public.root') . '/reviews');

        Review::truncate();

        $totalProducts = Product::where('is_variation', 0)->count();
        $totalCustomers = Customer::count();

        $reviews = [
            'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!',
            'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!',
            'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.',
            'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.',
            'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.',
            'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.',
            'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.',
            'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..',
            'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.',
            'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.',
            'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)',
            'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.',
            'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.',
            'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams',
            'The code is good, in general, if you like it, can you give it 5 stars?',
            'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.',
            'Good app, good backup service and support. Good documentation.',
            'Clean & perfect source code',
            'Best ecommerce CMS online store!',
            'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece',
            'We have received brilliant service support and will be expanding the features with the developer. Nice product!',
            'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.',
            'Great E-commerce system. And much more : Wonderful Customer Support.',
            'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)',
            'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)',
            'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.',
        ];

        for ($i = 0; $i < 100; $i++) {
            $images = [
                'products/' . rand(1, 4) . '.jpg',
                'products/' . rand(5, 7) . '.jpg',
                'products/' . rand(8, $totalProducts) . '.jpg',
            ];

            Review::create([
                'product_id'  => rand(1, $totalProducts),
                'customer_id' => rand(1, $totalCustomers),
                'star'        => rand(1, 5),
                'comment'     => $reviews[rand(0, count($reviews) - 1)],
                'status'      => BaseStatusEnum::PUBLISHED,
                'images'      => collect($images)
                    ->filter(function ($item, $key) {
                        return $key <= rand(0, 2);
                    })
                    ->toArray(),
            ]);
        }
    }
}
