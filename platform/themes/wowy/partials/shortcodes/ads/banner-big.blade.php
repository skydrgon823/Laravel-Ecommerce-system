<div class="banner-img banner-big wow fadeIn animated">
    <img src="{{ RvMedia::getImageUrl($ads->image) }}" alt="{{ $ads->name }}">
    <div class="banner-text">
        <h4 class="mb-15 mt-40 text-white">{{ $ads->name }}</h4>
        <h2 class="fw-600 mb-20 text-white">{!! nl2br($ads->getMetaData('subtitle', true)) !!}</h2>
        <a href="{{ route('public.ads-click', $ads->key) }}" class="btn">
            {{ $ads->getMetaData('button_text', true) ?: __('Shop Now') }} <i class="fa fa-arrow-right"></i>
        </a>
    </div>
</div>
