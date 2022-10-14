@if (is_plugin_active('simple-slider') && count($sliders) > 0 &&
    $sliders->loadMissing('metadata') && $slider->loadMissing('metadata'))
    @php
        $style = $slider->getMetaData('simple_slider_style', true);
    @endphp
    @if ($style == 'style-3')
        <section class="home-slider position-relative mt-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="position-relative">
                            <div class="hero-slider-1 style-3 dot-style-1 dot-style-1-position-1"  data-autoplay="{{ $shortcode->is_autoplay ?: 'yes' }}" data-autoplay-speed="{{ in_array($shortcode->autoplay_speed, theme_get_autoplay_speed_options()) ? $shortcode->autoplay_speed : 3000 }}">
                                @foreach($sliders as $slider)
                                    <div class="single-hero-slider single-animation-wrap">
                                        <div class="container">
                                            <div class="slider-1-height-3 slider-animated-1">
                                                {!! Theme::partial('shortcodes.sliders.content', compact('slider')) !!}
                                                <div class="slider-img">
                                                    <img src="{{ RvMedia::getImageUrl($slider->image, null, false, RvMedia::getDefaultImage()) }}" alt="image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="slider-arrow hero-slider-1-arrow style-3"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 d-md-none d-lg-block">
                        @if (is_plugin_active('ads'))
                            @foreach (get_ads_keys_from_shortcode($shortcode) as $key)
                                {!! display_ad($key, 'banner-' . ($loop->index + 1)) !!}
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @elseif ($style == 'style-4')
        <section class="home-slider position-relative mb-30 mt-30">
            <div class="container">
                <div class="home-slide-cover bg-grey-9">
                    {!! Theme::partial('shortcodes.sliders.grid', compact('sliders', 'shortcode') + ['class' => 'style-4']) !!}
                </div>
            </div>
        </section>
    @elseif ($style == 'style-2')
        <section class="home-slider bg-grey-9 position-relative">
            <div class="hero-slider-1 style-2 dot-style-1 dot-style-1-position-1" data-autoplay="{{ $shortcode->is_autoplay ?: 'yes' }}" data-autoplay-speed="{{ in_array($shortcode->autoplay_speed, theme_get_autoplay_speed_options()) ? $shortcode->autoplay_speed : 3000 }}">
                @foreach($sliders as $slider)
                    <div class="single-hero-slider single-animation-wrap">
                        <div class="container">
                            <div class="slider-1-height-2 slider-animated-1">
                                {!! Theme::partial('shortcodes.sliders.content', compact('slider')) !!}
                                <div class="single-slider-img single-slider-img-1">
                                    <img src="{{ RvMedia::getImageUrl($slider->image, null, false, RvMedia::getDefaultImage()) }}" alt="image">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="slider-arrow hero-slider-1-arrow"></div>
        </section>
    @else
        <section class="home-slider bg-grey-9 position-relative">
            {!! Theme::partial('shortcodes.sliders.grid', compact('sliders', 'shortcode')) !!}
        </section>
    @endif
@endif
