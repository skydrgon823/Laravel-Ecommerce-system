<div class="row promo coupon coupon-section" >
    <div class="col-lg-8 col-md-8 col-8">
        <input type="text" name="coupon_code" class="form-control coupon-code input-md checkout-input" value="{{ old('coupon_code') }}" placeholder="{{ __('Enter coupon code...') }}">
        <div class="coupon-error-msg">
            <span class="text-danger"></span>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-4 text-end">
        <button class="btn btn-md btn-gray btn-info apply-coupon-code float-end" data-url="{{ route('public.coupon.apply') }}" type="button" style="margin-top: 0;padding: 10px 20px;><i class="><i class="fa fa-gift"></i> {{ __('Apply') }}</button>
    </div>
</div>
