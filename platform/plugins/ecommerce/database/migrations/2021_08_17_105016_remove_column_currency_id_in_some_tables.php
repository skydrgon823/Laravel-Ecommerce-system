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
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->dropColumn('currency_id');
        });

        Schema::table('ec_shipping_rules', function (Blueprint $table) {
            $table->dropColumn('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->integer('currency_id')->unsigned()->nullable();
        });

        Schema::table('ec_shipping_rules', function (Blueprint $table) {
            $table->integer('currency_id')->unsigned()->nullable();
        });
    }
};
