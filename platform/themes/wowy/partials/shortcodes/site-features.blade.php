@php
    $headerStyle = theme_option('header_style') ?: '';
    $page = Theme::get('page');
    if ($page) {
        $headerStyle = $page->getMetaData('header_style', true) ?: $headerStyle;
    }
    $headerStyle = ($headerStyle && in_array($headerStyle, array_keys(get_layout_header_styles()))) ? $headerStyle : '';
@endphp
<section class="featured section-padding-60">
    <div class="container">
        <div class="row">
            @for ($i = 1; $i <= 5; $i++)
                @if (clean($shortcode->{'title' . $i}))
                    <div class="col-lg-3 col-md-6 mb-md-3 mb-lg-0">
                        <div class="banner-left-icon d-flex align-items-center wow fadeIn animated h-100 {{ $headerStyle == 'header-style-2' ? 'style-2' : '' }}">
                            <div class="banner-icon">
                                <img src="{{ RvMedia::getImageUrl($shortcode->{'icon' . $i}, null, false, RvMedia::getDefaultImage()) }}" alt="icon">
                            </div>
                            <div class="banner-text">
                                <h3 class="icon-box-title">{!! BaseHelper::clean($shortcode->{'title' . $i}) !!}</h3>
                                <p>{!! BaseHelper::clean($shortcode->{'subtitle' . $i}) !!}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endfor
        </div>
    </div>
</section>
