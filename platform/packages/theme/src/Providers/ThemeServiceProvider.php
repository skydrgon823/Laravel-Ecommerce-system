<?php

namespace Botble\Theme\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Theme\Commands\ThemeActivateCommand;
use Botble\Theme\Commands\ThemeAssetsPublishCommand;
use Botble\Theme\Commands\ThemeAssetsRemoveCommand;
use Botble\Theme\Commands\ThemeOptionCheckMissingCommand;
use Botble\Theme\Commands\ThemeRemoveCommand;
use Botble\Theme\Commands\ThemeRenameCommand;
use Botble\Theme\Contracts\Theme as ThemeContract;
use Botble\Theme\Http\Middleware\AdminBarMiddleware;
use Botble\Theme\Supports\ThemeSupport;
use Botble\Theme\Theme;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Theme as ThemeFacade;

class ThemeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->setNamespace('packages/theme')
            ->loadHelpers();

        $this->app->bind(ThemeContract::class, Theme::class);

        $this->commands([
            ThemeActivateCommand::class,
            ThemeRemoveCommand::class,
            ThemeAssetsPublishCommand::class,
            ThemeOptionCheckMissingCommand::class,
            ThemeAssetsRemoveCommand::class,
            ThemeRenameCommand::class,
        ]);
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-appearance',
                    'priority'    => 996,
                    'parent_id'   => null,
                    'name'        => 'packages/theme::theme.appearance',
                    'icon'        => 'fa fa-paint-brush',
                    'url'         => '#',
                    'permissions' => [],
                ]);

            $this->app['router']->pushMiddlewareToGroup('web', AdminBarMiddleware::class);

            if ($this->app['config']->get('packages.theme.general.display_theme_manager_in_admin_panel', true)) {
                dashboard_menu()
                    ->registerItem([
                        'id'          => 'cms-core-theme',
                        'priority'    => 1,
                        'parent_id'   => 'cms-core-appearance',
                        'name'        => 'packages/theme::theme.name',
                        'icon'        => null,
                        'url'         => route('theme.index'),
                        'permissions' => ['theme.index'],
                    ]);
            }

            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-theme-option',
                    'priority'    => 4,
                    'parent_id'   => 'cms-core-appearance',
                    'name'        => 'packages/theme::theme.theme_options',
                    'icon'        => null,
                    'url'         => route('theme.options'),
                    'permissions' => ['theme.options'],
                ])
                ->registerItem([
                    'id'          => 'cms-core-appearance-custom-css',
                    'priority'    => 5,
                    'parent_id'   => 'cms-core-appearance',
                    'name'        => 'packages/theme::theme.custom_css',
                    'icon'        => null,
                    'url'         => route('theme.custom-css'),
                    'permissions' => ['theme.custom-css'],
                ]);

            if (config('packages.theme.general.enable_custom_js')) {
                dashboard_menu()
                    ->registerItem([
                        'id'          => 'cms-core-appearance-custom-js',
                        'priority'    => 6,
                        'parent_id'   => 'cms-core-appearance',
                        'name'        => 'packages/theme::theme.custom_js',
                        'icon'        => null,
                        'url'         => route('theme.custom-js'),
                        'permissions' => ['theme.custom-js'],
                    ]);
            }

            if (config('packages.theme.general.enable_custom_html')) {
                dashboard_menu()
                    ->registerItem([
                        'id'          => 'cms-core-appearance-custom-html',
                        'priority'    => 6,
                        'parent_id'   => 'cms-core-appearance',
                        'name'        => 'packages/theme::theme.custom_html',
                        'icon'        => null,
                        'url'         => route('theme.custom-html'),
                        'permissions' => ['theme.custom-html'],
                    ]);
            }

            ThemeFacade::composer('*', function () {
                if (Auth::check()) {
                    if (Auth::user()->hasPermission('theme.index')) {
                        admin_bar()->registerLink(trans('packages/theme::theme.name'), route('theme.index'), 'appearance');
                    }

                    if (Auth::user()->hasPermission('theme.options')) {
                        admin_bar()->registerLink(trans('packages/theme::theme.theme_options'), route('theme.options'), 'appearance');
                    }
                }
            });
        });

        $this->app->booted(function () {
            $file = ThemeFacade::getStyleIntegrationPath();
            if (File::exists($file)) {
                ThemeFacade::asset()
                    ->container('after_header')
                    ->usePath()
                    ->add('theme-style-integration-css', 'css/style.integration.css', [], [], filectime($file));
            }

            if (!$this->app->environment('demo')) {
                if (config('packages.theme.general.enable_custom_js')) {
                    if (setting('custom_header_js')) {
                        add_filter(THEME_FRONT_HEADER, function ($html) {
                            return $html . ThemeSupport::getCustomJS('header');
                        }, 15);
                    }

                    if (setting('custom_body_js')) {
                        add_filter(THEME_FRONT_BODY, function ($html) {
                            return $html . ThemeSupport::getCustomJS('body');
                        }, 15);
                    }

                    if (setting('custom_footer_js')) {
                        add_filter(THEME_FRONT_FOOTER, function ($html) {
                            return $html . ThemeSupport::getCustomJS('footer');
                        }, 15);
                    }
                }

                if (config('packages.theme.general.enable_custom_html')) {
                    if (setting('custom_header_html')) {
                        add_filter(THEME_FRONT_HEADER, function ($html) {
                            return $html . ThemeSupport::getCustomHtml('header');
                        }, 16);
                    }

                    if (setting('custom_body_html')) {
                        add_filter(THEME_FRONT_BODY, function ($html) {
                            return $html . ThemeSupport::getCustomHtml('body');
                        }, 16);
                    }

                    if (setting('custom_footer_html')) {
                        add_filter(THEME_FRONT_FOOTER, function ($html) {
                            return $html . ThemeSupport::getCustomHtml('footer');
                        }, 16);
                    }
                }
            }

            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(ThemeManagementServiceProvider::class);
    }
}
