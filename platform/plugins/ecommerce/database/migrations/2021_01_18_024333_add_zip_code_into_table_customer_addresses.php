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
        Schema::table('ec_customer_addresses', function (Blueprint $table) {
            $table->string('zip_code', 20)->nullable();
        });

        Schema::table('ec_order_addresses', function (Blueprint $table) {
            $table->string('zip_code', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_customer_addresses', function (Blueprint $table) {
            $table->dropColumn('zip_code');
        });

        Schema::table('ec_order_addresses', function (Blueprint $table) {
            $table->dropColumn('zip_code');
        });
    }
};
