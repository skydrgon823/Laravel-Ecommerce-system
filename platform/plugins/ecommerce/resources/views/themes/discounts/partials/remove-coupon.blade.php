<div class="row promo">
    <div class="col-lg-8 col-md-8 col-8">
        <div class="alert alert-success coupon-text" style="padding: 12px;">
           {{ __('Coupon code: :code', ['code' => session('applied_coupon_code')]) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-4 text-end">
        <button class="btn btn-md btn-gray btn-warning remove-coupon-code" data-url="{{ route('public.coupon.remove') }}" type="button" style="padding: 12px;"><i class="fa fa-trash"></i> {{ __('Remove') }}</button>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <div class="coupon-error-msg">
            <span class="text-danger"></span>
        </div>
    </div>
</div>
