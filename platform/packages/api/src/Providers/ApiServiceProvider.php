<?php

namespace Botble\Api\Providers;

use ApiHelper;
use Botble\Api\Facades\ApiHelperFacade;
use Botble\Api\Http\Middleware\ForceJsonResponseMiddleware;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class ApiServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        AliasLoader::getInstance()->alias('ApiHelper', ApiHelperFacade::class);
    }

    public function boot()
    {
        $this
            ->setNamespace('packages/api')
            ->loadRoutes(['web'])
            ->loadAndPublishConfigurations(['api', 'permissions'])
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->loadAndPublishViews();

        if (ApiHelper::enabled()) {
            $this->loadRoutes(['api']);
        }

        Event::listen(RouteMatched::class, function () {
            $this->app['router']->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);

            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-packages-api',
                    'priority'    => 9999,
                    'parent_id'   => 'cms-core-settings',
                    'name'        => 'packages/api::api.settings',
                    'icon'        => null,
                    'url'         => route('api.settings'),
                    'permissions' => ['api.settings'],
                ]);
        });

        $this->app->booted(function () {
            config([
                'scribe.routes.0.match.prefixes' => ['api/*'],
                'scribe.routes.0.apply.headers'  => [
                    'Authorization' => 'Bearer {token}',
                    'Api-Version'   => 'v1',
                ],
            ]);
        });
    }
}
