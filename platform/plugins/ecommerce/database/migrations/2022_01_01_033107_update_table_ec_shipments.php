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
        Schema::table('ec_shipments', function (Blueprint $table) {
            $table->string('tracking_id')->nullable();
            $table->string('shipping_company_name')->nullable();
            $table->string('tracking_link')->nullable();
            $table->dateTime('estimate_date_shipped')->nullable();
            $table->dateTime('date_shipped')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_shipments', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_id',
                'shipping_company_name',
                'tracking_link',
                'estimate_date_shipped',
                'date_shipped',
            ]);
        });
    }
};
