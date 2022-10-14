@if (Cart::instance('cart')->count() > 0)
    @php
        $products = get_products([
            'condition' => [
                ['ec_products.id', 'IN', Cart::instance('cart')->content()->pluck('id')->all()],
            ],
            'with' => ['slugable'],
        ]);
    @endphp
    @if (count($products))
        <ul>
            @foreach(Cart::instance('cart')->content() as $key => $cartItem)
                @php
                    $product = $products->find($cartItem->id);
                @endphp

                @if (!empty($product))
                    <li>
                        <div class="shopping-cart-img">
                            <a href="{{ $product->original_product->url }}"><img alt="{{ $product->name }}" src="{{ $cartItem->options['image'] }}"></a>
                        </div>
                        <div class="shopping-cart-title">
                            <h4><a href="{{ $product->original_product->url }}">{{ $product->name }}  @if ($product->isOutOfStock()) <span class="stock-status-label">({!! $product->stock_status_html !!})</span> @endif</a></h4>
                            <h3><span class="d-inline-block">{{ $cartItem->qty }}</span> <span class="d-inline-block"> x </span> <span class="d-inline-block">{{ format_price($cartItem->price) }}</span> @if ($product->front_sale_price != $product->price)
                                    <small><del>{{ format_price($product->price) }}</del></small>@endif</h3>
                            <p class="mb-0"><small>{{ $cartItem->options['attributes'] ?? '' }}</small></p>
                            @if (!empty($cartItem->options['extras']) && is_array($cartItem->options['extras']))
                                @foreach($cartItem->options['extras'] as $option)
                                    @if (!empty($option['key']) && !empty($option['value']))
                                        <p class="mb-0"><small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small></p>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="shopping-cart-delete">
                            <a href="#" data-url="{{ route('public.cart.remove', $cartItem->rowId) }}" class="remove-cart-item"><i class="far fa-times"></i></a>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    @endif
    <div class="shopping-cart-footer">
        <div class="shopping-cart-total">
            @if (EcommerceHelper::isTaxEnabled())
                <h5><strong class="d-inline-block">{{ __('Sub Total') }}:</strong> <span>{{ format_price(Cart::instance('cart')->rawSubTotal()) }}</span></h5>
                <div class="clearfix"></div>
                <h5><strong class="d-inline-block">{{ __('Tax') }}:</strong> <span>{{ format_price(Cart::instance('cart')->rawTax()) }}</span></h5>
                <div class="clearfix"></div>
                <h4><strong class="d-inline-block">{{ __('Total') }}:</strong> <span>{{ format_price(Cart::instance('cart')->rawSubTotal() + Cart::instance('cart')->rawTax()) }}</span></h4>
            @else
                <h4><strong class="d-inline-block">{{ __('Sub Total') }}:</strong> <span>{{ format_price(Cart::instance('cart')->rawSubTotal()) }}</span></h4>
            @endif
        </div>
        <div class="shopping-cart-button">
            <a href="{{ route('public.cart') }}">{{ __('View cart') }}</a>
            @if (session('tracked_start_checkout'))
                <a href="{{ route('public.checkout.information', session('tracked_start_checkout')) }}">{{ __('Checkout') }}</a>
            @endif
        </div>
    </div>
@else
    <span>{{ __('No products in the cart.') }}</span>
@endif
