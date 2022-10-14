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
        Schema::table('ec_product_attribute_sets', function (Blueprint $table) {
            $table->tinyInteger('use_image_from_product_variation')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_product_attribute_sets', function (Blueprint $table) {
            $table->dropColumn('use_image_from_product_variation');
        });
    }
};
