<?php

use Botble\PluginManagement\Services\PluginService;
use Illuminate\Database\Migrations\Migration;

class ActivatePaypalStripePlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pluginService = app(PluginService::class);
        $pluginService->activate('stripe');
        $pluginService->activate('paypal');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
