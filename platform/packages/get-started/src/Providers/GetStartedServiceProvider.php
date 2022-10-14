<?php

namespace Botble\GetStarted\Providers;

use Assets;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class GetStartedServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('packages/get-started')
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes(['web'])
            ->loadAndPublishViews();

        $this->app->booted(function () {
            add_action(DASHBOARD_ACTION_REGISTER_SCRIPTS, function () {
                if ($this->shouldShowGetStartedPopup()) {
                    Assets::addScriptsDirectly('vendor/core/packages/get-started/js/get-started.js')
                        ->addStylesDirectly('vendor/core/packages/get-started/css/get-started.css')
                        ->addScripts(['are-you-sure', 'colorpicker', 'jquery-ui'])
                        ->addStyles(['colorpicker']);

                    add_filter(BASE_FILTER_FOOTER_LAYOUT_TEMPLATE, function ($html) {
                        return $html . view('packages/get-started::index')->render();
                    }, 120);

                    add_filter(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, function ($html) {
                        return $html . view('packages/get-started::setup-wizard-notice')->render();
                    }, 4);
                }
            }, 234);
        });
    }

    /**
     * @return bool
     */
    protected function shouldShowGetStartedPopup(): bool
    {
        return !$this->app->environment('demo') &&
            is_in_admin() &&
            Auth::check() &&
            setting('is_completed_get_started') != '1';
    }
}
