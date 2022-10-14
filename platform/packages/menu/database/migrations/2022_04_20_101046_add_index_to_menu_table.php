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
        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->index('reference_id', 'reference_id');
            $table->index('reference_type', 'reference_type');
        });

        Schema::table('menu_locations', function (Blueprint $table) {
            $table->index(['menu_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->dropIndex('reference_id');
            $table->dropIndex('reference_type');
        });

        Schema::table('menu_locations', function (Blueprint $table) {
            $table->dropIndex(['menu_id', 'created_at']);
        });
    }
};
