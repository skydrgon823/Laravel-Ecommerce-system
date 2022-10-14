<?php

use Botble\Ecommerce\Enums\OrderStatusEnum;
use Carbon\Carbon;
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
        if (!Schema::hasColumn('ec_orders', 'completed_at')) {
            Schema::table('ec_orders', function (Blueprint $table) {
                $table->timestamp('completed_at')->after('is_finished')->nullable();
            });
        }

        DB::table('ec_orders')->where('status', OrderStatusEnum::COMPLETED)->update(['completed_at' => Carbon::now()]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
};
