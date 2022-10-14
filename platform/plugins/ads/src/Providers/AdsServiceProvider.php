<?php

namespace Botble\Ads\Providers;

use AdsManager;
use Botble\Ads\Facades\AdsManagerFacade;
use Botble\Ads\Models\Ads;
use Botble\Ads\Repositories\Caches\AdsCacheDecorator;
use Botble\Ads\Repositories\Eloquent\AdsRepository;
use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AdsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(AdsInterface::class, function () {
            return new AdsCacheDecorator(new AdsRepository(new Ads()));
        });

        Helper::autoload(__DIR__ . '/../../helpers');

        AliasLoader::getInstance()->alias('AdsManager', AdsManagerFacade::class);
    }

    public function boot()
    {
        $this->setNamespace('plugins/ads')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadAndPublishViews();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-ads',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/ads::ads.name',
                'icon'        => 'fas fa-bullhorn',
                'url'         => route('ads.index'),
                'permissions' => ['ads.index'],
            ]);
        });

        if (function_exists('shortcode')) {
            add_shortcode('ads', 'Ads', 'Ads', function ($shortcode) {
                if (!$shortcode->key) {
                    return null;
                }

                return AdsManager::displayAds((string)$shortcode->key);
            });

            shortcode()->setAdminConfig('ads', function ($attributes) {
                $ads = $this->app->make(AdsInterface::class)
                    ->pluck('name', 'key', ['status' => BaseStatusEnum::PUBLISHED]);

                return view('plugins/ads::partials.ads-admin-config', compact('ads', 'attributes'))
                    ->render();
            });
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Ads::class, [
                'name',
                'image',
                'url',
            ]);
        }

        add_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, function ($request, $data = null) {
            if (!$data instanceof Ads || !in_array(Route::currentRouteName(), ['ads.create', 'ads.edit'])) {
                return false;
            }

            echo view('plugins/ads::partials.notification')
                ->render();

            return true;
        }, 45, 2);
    }
}
