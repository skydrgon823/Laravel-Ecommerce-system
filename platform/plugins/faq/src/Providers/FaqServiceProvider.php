<?php

namespace Botble\Faq\Providers;

use Botble\Faq\Models\FaqCategory;
use Botble\Faq\Repositories\Caches\FaqCategoryCacheDecorator;
use Botble\Faq\Repositories\Eloquent\FaqCategoryRepository;
use Botble\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Faq\Models\Faq;
use Botble\Faq\Repositories\Caches\FaqCacheDecorator;
use Botble\Faq\Repositories\Eloquent\FaqRepository;
use Botble\Faq\Repositories\Interfaces\FaqInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Language;

class FaqServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(FaqCategoryInterface::class, function () {
            return new FaqCategoryCacheDecorator(new FaqCategoryRepository(new FaqCategory()));
        });

        $this->app->bind(FaqInterface::class, function () {
            return new FaqCacheDecorator(new FaqRepository(new Faq()));
        });
    }

    public function boot()
    {
        $this
            ->setNamespace('plugins/faq')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->publishAssets();

        $useLanguageV2 = $this->app['config']->get('plugins.faq.general.use_language_v2', false) &&
            defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME');

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if ($useLanguageV2) {
                LanguageAdvancedManager::registerModule(Faq::class, [
                    'question',
                    'answer',
                ]);
                LanguageAdvancedManager::registerModule(FaqCategory::class, [
                    'name',
                ]);
            } else {
                $this->app->booted(function () {
                    Language::registerModule([Faq::class, FaqCategory::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-faq',
                    'priority'    => 5,
                    'parent_id'   => null,
                    'name'        => 'plugins/faq::faq.name',
                    'icon'        => 'far fa-question-circle',
                    'url'         => route('faq.index'),
                    'permissions' => ['faq.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-faq-list',
                    'priority'    => 0,
                    'parent_id'   => 'cms-plugins-faq',
                    'name'        => 'plugins/faq::faq.all',
                    'icon'        => null,
                    'url'         => route('faq.index'),
                    'permissions' => ['faq.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-packages-faq-category',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-faq',
                    'name'        => 'plugins/faq::faq-category.name',
                    'icon'        => null,
                    'url'         => route('faq_category.index'),
                    'permissions' => ['faq_category.index'],
                ]);
        });

        $this->app->register(HookServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }
}
