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
        if (!Schema::hasTable('ec_order_return_items')) {
            Schema::create('ec_order_return_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('order_return_id')->comment('Order return id');
                $table->unsignedInteger('order_product_id')->comment('Order product id');
                $table->unsignedInteger('product_id')->comment('Product id');
                $table->string('product_name');
                $table->integer('qty')->comment('Quantity return');
                $table->decimal('price', 15, 2)->comment('Price Product');
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_order_return_items');
    }
};
