<?php

namespace Botble\Theme\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Theme;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Move base routes to a service provider to make sure all filters & actions can hook to base routes
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->loadRoutesFrom(theme_path(Theme::getThemeName() . '/routes/web.php'));
        });
    }
}
