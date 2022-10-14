<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->dateTime('end_date');
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('ec_flash_sale_products', function (Blueprint $table) {
            $table->integer('flash_sale_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->double('price')->unsigned()->nullable();
            $table->integer('quantity')->unsigned()->nullable();
            $table->integer('sold')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_flash_sale_products');
        Schema::dropIfExists('ec_flash_sales');
    }
};
