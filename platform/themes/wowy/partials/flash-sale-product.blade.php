<div class="deal wow fadeIn animated mb-md-4 mb-sm-4 mb-lg-0" style="background-image: url({{ RvMedia::getImageUrl(MetaBox::getMetaData($flashSale, 'image', true), null, false, RvMedia::getDefaultImage()) }});">
    <div class="deal-top">
        <h2 class="text-brand">{{ $flashSale->name }}</h2>
        <h5>{!! BaseHelper::clean(MetaBox::getMetaData($flashSale, 'subtitle', true)) !!}</h5>
    </div>
    <div class="deal-content">
        <h6 class="product-title"><a href="{{ $product->url }}">{{ $product->name }}</a></h6>
        <div class="product-price"><span class="new-price">{{ format_price($product->front_sale_price_with_taxes) }}</span>
            @if ($product->front_sale_price !== $product->price)
                <span class="old-price">{{ format_price($product->price_with_taxes) }}</span>
            @endif
        </div>
    </div>
    <div class="deal-bottom">
        <p>{{ __('Hurry Up! Offer End In:') }}</p>
        <div class="deals-countdown" data-countdown="{{ $flashSale->end_date }}"></div>
        <a href="{{ $product->url }}" class="btn hover-up">{{ __('Shop Now') }} <i class="fa fa-arrow-right"></i></a>
    </div>
</div>
