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
        if (!Schema::hasTable('ec_order_returns')) {
            Schema::create('ec_order_returns', function (Blueprint $table) {
                $table->id();
                $table->integer('order_id')->unsigned()->comment('Order ID');
                $table->integer('store_id')->unsigned()->nullable()->comment('Store ID');
                $table->integer('user_id')->unsigned()->comment('Customer ID');
                $table->text('reason')->nullable()->comment('Reason return order');
                $table->string('order_status')->nullable()->comment('Order current status');
                $table->string('return_status')->comment('Return status');
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
        Schema::dropIfExists('ec_order_returns');
    }
};
