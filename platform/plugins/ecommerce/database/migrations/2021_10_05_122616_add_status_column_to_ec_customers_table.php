<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Botble\Ecommerce\Enums\CustomerStatusEnum;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('ec_customers', 'status')) {
            Schema::table('ec_customers', function (Blueprint $table) {
                $table->string('status', 60)->default(CustomerStatusEnum::ACTIVATED);
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
        if (Schema::hasColumn('ec_customers', 'status')) {
            Schema::table('ec_customers', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
