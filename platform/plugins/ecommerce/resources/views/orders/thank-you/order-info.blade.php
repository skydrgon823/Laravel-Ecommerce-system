<div class="pt-3 mb-4">
    <div class="align-items-center">
        <h6 class="d-inline-block">{{ __('Order number') }}: {{ get_order_code($order->id) }}</h6>
    </div>

    <div class="checkout-success-products">
        <div class="row show-cart-row d-md-none p-2">
            <div class="col-9">
                <a class="show-cart-link"
                   href="javascript:void(0);"
                   data-bs-toggle="collapse"
                   data-bs-target="{{ '#cart-item-' . $order->id }}">
                    {{ __('Order information :order_id', ['order_id' => get_order_code($order->id)]) }} <i class="fa fa-angle-down" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-3">
                <p class="text-end mobile-total"> {{ format_price($order->amount) }} </p>
            </div>
        </div>
        <div id="{{ 'cart-item-' . $order->id }}" class="collapse collapse-products">
            @foreach ($order->products as $orderProduct)
                @php
                    $product = get_products([
                        'condition' => [
                            'ec_products.id' => $orderProduct->product_id,
                        ],
                        'take'   => 1,
                        'select' => [
                            'ec_products.id',
                            'ec_products.image',
                            'ec_products.images',
                            'ec_products.name',
                            'ec_products.price',
                            'ec_products.sale_price',
                            'ec_products.sale_type',
                            'ec_products.start_date',
                            'ec_products.end_date',
                            'ec_products.sku',
                            'ec_products.is_variation',
                            'ec_products.status',
                            'ec_products.order',
                            'ec_products.created_at',
                        ],
                    ]);
                @endphp

                @if ($product)
                    <div class="row cart-item">
                    <div class="col-lg-3 col-md-3">
                        <div class="checkout-product-img-wrapper">
                            <img class="item-thumb img-thumbnail img-rounded"
                                 src="{{ RvMedia::getImageUrl($product->image ?: $product->original_product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                 alt="{{ $product->name . '(' . $product->sku . ')'}}">
                            <span class="checkout-quantity">{{ $orderProduct->qty }}</span>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <p class="mb-0">{{ $product->name }}</p>
                        <p class="mb-0">
                            <small>{{ $product->variation_attributes }}</small>
                        </p>

                        @if (!empty($orderProduct->options) && is_array($orderProduct->options))
                            @foreach($orderProduct->options as $option)
                                @if (!empty($option['key']) && !empty($option['value']))
                                    <p class="mb-0">
                                        <small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small>
                                    </p>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="col-lg-4 col-md-4 col-4 float-end text-end">
                        <p>{{ format_price($orderProduct->price) }}</p>
                    </div>
                </div> <!--  /item -->
                @endif
            @endforeach

            @if (!empty($isShowTotalInfo))
                @include('plugins/ecommerce::orders.thank-you.total-info', compact('order'))
            @endif
        </div>
    </div>
</div>
