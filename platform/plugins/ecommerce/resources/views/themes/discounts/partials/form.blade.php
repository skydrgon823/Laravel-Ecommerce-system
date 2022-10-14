<div class="checkout-discount-section" @if (session()->has('applied_coupon_code')) style="display: none;" @endif>
    <a href="#" class="btn-open-coupon-form">{{ __('You have a coupon code?') }}</a>
</div>
<div class="coupon-wrapper" @if (!session()->has('applied_coupon_code')) style="display: none;" @endif>
    @if (!session()->has('applied_coupon_code'))
        @include('plugins/ecommerce::themes.discounts.partials.apply-coupon')
    @else
        @include('plugins/ecommerce::themes.discounts.partials.remove-coupon')
    @endif
</div>
<div class="clearfix"></div>
