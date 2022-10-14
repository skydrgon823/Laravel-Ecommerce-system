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
            if (!Schema::hasColumn('ec_reviews', 'images')) {
                $table->text('images')->nullable();
            }
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
            if (Schema::hasColumn('ec_reviews', 'images')) {
                $table->dropColumn('images');
            }
        });
    }
};
