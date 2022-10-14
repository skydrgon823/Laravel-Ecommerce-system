@if (isset($products) && $products)
    <p>{{ __('Product(s)') }}:</p>
    @foreach($products as $key => $product)
        @php
            $cartItem = $product->cartItem;
        @endphp

        @if (!empty($product))
            @include('plugins/ecommerce::orders.checkout.product')
        @endif
    @endforeach

    <hr>
@endif
