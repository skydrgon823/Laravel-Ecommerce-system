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
        if (!Schema::hasTable('ec_product_tags_translations')) {
            Schema::create('ec_product_tags_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->integer('ec_product_tags_id');
                $table->string('name')->nullable();

                $table->primary(['lang_code', 'ec_product_tags_id'], 'ec_product_tags_translations_primary');
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
        Schema::dropIfExists('ec_product_tags_translations');
    }
};
