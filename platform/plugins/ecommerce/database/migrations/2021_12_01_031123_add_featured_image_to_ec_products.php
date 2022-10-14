<?php

use Botble\Ecommerce\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('ec_products', 'image')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->string('image', 255)->nullable();
            });

            foreach (Product::where('is_variation', 0) as $product) {
                $product->image = Arr::first($product->images) ?: null;
                $product->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('ec_products', 'image')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
