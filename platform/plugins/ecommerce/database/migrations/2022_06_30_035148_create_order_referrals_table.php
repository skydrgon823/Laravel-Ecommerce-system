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
        Schema::dropIfExists('ec_order_referrals');

        Schema::create('ec_order_referrals', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 39)->nullable();
            $table->string('landing_domain', 255)->nullable();
            $table->string('landing_page', 255)->nullable();
            $table->string('landing_params', 255)->nullable();
            $table->string('referral', 255)->nullable();
            $table->string('gclid', 255)->nullable();
            $table->string('fclid', 255)->nullable();
            $table->string('utm_source', 255)->nullable();
            $table->string('utm_campaign', 255)->nullable();
            $table->string('utm_medium', 255)->nullable();
            $table->string('utm_term', 255)->nullable();
            $table->string('utm_content', 255)->nullable();
            $table->text('referrer_url')->nullable();
            $table->string('referrer_domain', 255)->nullable();
            $table->integer('order_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_order_referrals');
    }
};
