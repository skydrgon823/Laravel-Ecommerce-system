<section class="deals mb-60">
    <div class="container">
        <flash-sale-products-component url="{{ route('public.ajax.get-flash-sales', ['limit' => $limit]) }}"></flash-sale-products-component>
    </div>
</section>

@if ($flashSale && $showPopup == 'yes' && $flashSale->products->count())
    @php
        $product = $flashSale->products->random();
        $flashSale->load('metadata');
        $subtitleKey = 'subtitle';
        if (is_plugin_active('language-advanced') && Language::getCurrentLocaleCode() != Language::getDefaultLocaleCode()) {
            $subtitleKey = Language::getCurrentLocaleCode() . '_subtitle';
        }
    @endphp
    <div class="modal fade custom-modal" id="flash-sale-modal" data-id="flash-sale-id-{{ $flashSale->id }}" tabindex="-1" aria-labelledby="onloadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="deal" style="background-image: url({{ RvMedia::getImageUrl($flashSale->getMetaData('image', true), null, false, RvMedia::getDefaultImage()) }});">
                        <div class="deal-top">
                            <h2 class="text-brand">{{ $flashSale->name }}</h2>
                            <h5>{!! BaseHelper::clean($flashSale->getMetaData($subtitleKey, true)) !!}</h5>
                        </div>
                        <div class="deal-content">
                            <h6 class="product-title"><a href="{{ $product->url }}">{{ $product->name }}</a></h6>
                            <div class="product-price">
                                <span class="new-price">{{ format_price($product->front_sale_price_with_taxes) }}</span>
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
                </div>
            </div>
        </div>
    </div>
@endif
