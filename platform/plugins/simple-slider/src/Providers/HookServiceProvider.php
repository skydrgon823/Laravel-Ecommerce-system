<?php

namespace Botble\SimpleSlider\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Illuminate\Support\ServiceProvider;
use Theme;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (function_exists('shortcode')) {
            add_shortcode(
                'simple-slider',
                trans('plugins/simple-slider::simple-slider.simple_slider_shortcode_name'),
                trans('plugins/simple-slider::simple-slider.simple_slider_shortcode_description'),
                [$this, 'render']
            );

            shortcode()->setAdminConfig('simple-slider', function ($attributes) {
                $sliders = $this->app->make(SimpleSliderInterface::class)
                    ->pluck('name', 'key', ['status' => BaseStatusEnum::PUBLISHED]);

                return view('plugins/simple-slider::partials.simple-slider-admin-config', compact('sliders', 'attributes'))
                    ->render();
            });
        }

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 301);
    }

    /**
     * @param Shortcode $shortcode
     * @return null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function render($shortcode)
    {
        $slider = $this->app->make(SimpleSliderInterface::class)->getFirstBy([
            'key'    => $shortcode->key,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        if (empty($slider)) {
            return null;
        }

        if (setting('simple_slider_using_assets', true) && defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
            $version = '1.0.1';
            $dist = asset('vendor/core/plugins/simple-slider');

            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add('simple-slider-owl-carousel-css', $dist . '/libraries/owl-carousel/owl.carousel.css', [], [], $version)
                ->add('simple-slider-css', $dist . '/css/simple-slider.css', [], [], $version)
                ->add('simple-slider-owl-carousel-js', $dist . '/libraries/owl-carousel/owl.carousel.js', ['jquery'], [], $version)
                ->add('simple-slider-js', $dist . '/js/simple-slider.js', ['jquery'], [], $version);
        }

        return view(apply_filters(SIMPLE_SLIDER_VIEW_TEMPLATE, 'plugins/simple-slider::sliders'), [
            'sliders'   => $slider->sliderItems,
            'shortcode' => $shortcode,
            'slider'    => $slider,
        ]);
    }

    /**
     * @param null $data
     * @return string
     * @throws \Throwable
     */
    public function addSettings($data = null)
    {
        return $data . view('plugins/simple-slider::setting')->render();
    }
}
