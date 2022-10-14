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
        Schema::create('ec_product_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('color', 120)->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('ec_product_label_products', function (Blueprint $table) {
            $table->integer('product_label_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->primary(['product_label_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_product_label_products');
        Schema::dropIfExists('ec_product_labels');
    }
};
