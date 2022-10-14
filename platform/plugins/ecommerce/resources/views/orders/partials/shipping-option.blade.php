<li class="list-group-item">
    <input
            class="magic-radio"
            type="radio"
            name="shipping_method"
            id="shipping-method-{{ $shippingKey }}-{{ $shippingOption }}"
            @if (old('shipping_method', $defaultShippingMethod) == $shippingKey && old('shipping_option', $defaultShippingOption) == $shippingOption) checked @endif
            value="{{ $shippingKey }}"
            data-option="{{ $shippingOption }}"
    >
    <label for="shipping-method-{{ $shippingKey }}-{{ $shippingOption }}">{{ $shippingItem['name'] }} - {{ format_price($shippingItem['price']) }}</strong></label>
</li>
