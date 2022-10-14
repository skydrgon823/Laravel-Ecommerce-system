<div class="banner-img wow fadeIn animated {{ $class ?? '' }}">
    <img class="border-radius-10" src="{{ RvMedia::getImageUrl($ads->image) }}" alt="{{ $ads->name }}">
    <div class="banner-text">
        <span>{{ $ads->name }}</span>
        <h4>{!! BaseHelper::clean(nl2br($ads->getMetaData('subtitle', true) ?: '')) !!}</h4>
        <a href="{{ route('public.ads-click', $ads->key) }}">
            {{ $ads->getMetaData('button_text', true) ?: __('Shop Now') }} <i class="fa fa-arrow-right"></i>
        </a>
    </div>
</div>
