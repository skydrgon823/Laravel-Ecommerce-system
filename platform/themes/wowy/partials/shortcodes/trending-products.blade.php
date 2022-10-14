<div class="nt_section type_featured_collection tp_se_cdt" id="trending-products">
    <div class="wowy-otp-01__feature container">
        @if (clean($title) || BaseHelper::clean($description))
            <div class="wrap_title des_title_2">
                @if (clean($title))
                    <h3 class="section-title tc pr flex fl_center al_center fs__24 title_2">
                        <span class="mr__10 ml__10">{!! BaseHelper::clean($title) !!}</span>
                    </h3>
                @endif
                @if (clean($description))
                    <span class="dn tt_divider">
                            <span></span>
                            <i class="dn clprfalse title_2 la-gem"></i>
                            <span></span>
                        </span>
                    <span class="section-subtitle d-block tc sub-title">{!! BaseHelper::clean($description) !!}</span>
                @endif
            </div>
        @endif
        <trending-products-component url="{{ route('public.ajax.trending-products', ['limit' => $limit]) }}"></trending-products-component>
    </div>
</div>
