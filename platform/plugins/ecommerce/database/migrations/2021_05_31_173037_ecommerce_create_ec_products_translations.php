<?php

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
        Schema::dropIfExists('ec_products_translations');
        Schema::dropIfExists('ec_product_categories_translations');
        Schema::dropIfExists('ec_product_attributes_translations');
        Schema::dropIfExists('ec_product_attribute_sets_translations');
        Schema::dropIfExists('ec_brands_translations');
        Schema::dropIfExists('ec_product_collections_translations');
        Schema::dropIfExists('ec_product_labels_translations');
        Schema::dropIfExists('ec_flash_sales_translations');

        Schema::create('ec_products_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_products_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();

            $table->primary(['lang_code', 'ec_products_id'], 'ec_products_translations_primary');
        });

        Schema::create('ec_product_categories_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_product_categories_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->primary(['lang_code', 'ec_product_categories_id'], 'ec_product_categories_translations_primary');
        });

        Schema::create('ec_product_attributes_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_product_attributes_id');
            $table->string('title')->nullable();

            $table->primary(['lang_code', 'ec_product_attributes_id'], 'ec_product_attributes_translations_primary');
        });

        Schema::create('ec_product_attribute_sets_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_product_attribute_sets_id');
            $table->string('title')->nullable();

            $table->primary(['lang_code', 'ec_product_attribute_sets_id'], 'ec_product_attribute_sets_translations_primary');
        });

        Schema::create('ec_brands_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_brands_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();

            $table->primary(['lang_code', 'ec_brands_id'], 'ec_brands_translations_primary');
        });

        Schema::create('ec_product_collections_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_product_collections_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();

            $table->primary(['lang_code', 'ec_product_collections_id'], 'ec_product_collections_translations_primary');
        });

        Schema::create('ec_product_labels_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_product_labels_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();

            $table->primary(['lang_code', 'ec_product_labels_id'], 'ec_product_labels_translations_primary');
        });

        Schema::create('ec_flash_sales_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('ec_flash_sales_id');
            $table->string('name')->nullable();

            $table->primary(['lang_code', 'ec_flash_sales_id'], 'ec_flash_sales_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_products_translations');
        Schema::dropIfExists('ec_product_categories_translations');
        Schema::dropIfExists('ec_product_attributes_translations');
        Schema::dropIfExists('ec_product_attribute_sets_translations');
        Schema::dropIfExists('ec_brands_translations');
        Schema::dropIfExists('ec_product_collections_translations');
        Schema::dropIfExists('ec_product_labels_translations');
        Schema::dropIfExists('ec_flash_sales_translations');
    }
};
