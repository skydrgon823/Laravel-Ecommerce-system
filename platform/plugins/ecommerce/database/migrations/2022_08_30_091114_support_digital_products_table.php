<?php

use Botble\Ecommerce\Enums\ProductTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->string('product_type', 60)->nullable()->default(ProductTypeEnum::PHYSICAL);
        });

        Schema::create('ec_product_files', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable()->index();
            $table->string('url', 400)->nullable();
            $table->mediumText('extras')->nullable(); // file name, size, mime_type...
            $table->timestamps();
        });

        Schema::table('ec_order_product', function (Blueprint $table) {
            $table->string('product_type', 60)->default(ProductTypeEnum::PHYSICAL);
            $table->integer('times_downloaded')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_order_product', function (Blueprint $table) {
            $table->dropColumn(['times_downloaded', 'product_type']);
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropColumn(['product_type']);
        });

        Schema::dropIfExists('ec_product_files');
    }
};
