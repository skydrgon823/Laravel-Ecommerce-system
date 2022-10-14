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
        Schema::table('ec_reviews', function (Blueprint $table) {
            $table->index(['product_id', 'customer_id', 'status']);
        });

        Schema::table('ec_wish_lists', function (Blueprint $table) {
            $table->index(['product_id', 'customer_id']);
        });

        Schema::table('ec_product_variation_items', function (Blueprint $table) {
            $table->index(['attribute_id', 'variation_id']);
        });

        Schema::table('ec_product_variations', function (Blueprint $table) {
            $table->index(['product_id', 'configurable_product_id']);
        });

        Schema::table('ec_product_attributes', function (Blueprint $table) {
            $table->index(['attribute_set_id', 'status']);
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->index('sale_type');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('sale_price');
            $table->index('is_variation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'customer_id', 'status']);
        });

        Schema::table('ec_wish_lists', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'customer_id']);
        });

        Schema::table('ec_product_variation_items', function (Blueprint $table) {
            $table->dropIndex(['attribute_id', 'variation_id']);
        });

        Schema::table('ec_product_variations', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'configurable_product_id']);
        });

        Schema::table('ec_product_attributes', function (Blueprint $table) {
            $table->dropIndex(['attribute_set_id', 'status']);
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropIndex('sale_type');
            $table->dropIndex('start_date');
            $table->dropIndex('end_date');
            $table->dropIndex('sale_price');
            $table->dropIndex('is_variation');
        });
    }
};
