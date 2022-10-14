<?php

namespace Botble\Ecommerce\Providers;

use Botble\Ecommerce\Commands\SendAbandonedCartsEmailCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            SendAbandonedCartsEmailCommand::class,
        ]);
    }
}
