<!--product recently viewed section-->
<div class="wowy-section tp_se_cdt">
    <div class="related product-extra mt__70 mb__80">
        <div class="container">
            <div class="wrap_title des_title_1 mb__30">
                <h3 class="section-title tc pr flex fl_center al_center fs__24 title_1">
                    <span class="mr__10 ml__10">{{ __('Recently viewed products') }}</span></h3>
            </div>

            <recently-viewed-products-component url="{{ route('public.ajax.recently-viewed-products') }}"></recently-viewed-products-component>
        </div>
    </div>
</div>
<!--end product recently viewed section-->
