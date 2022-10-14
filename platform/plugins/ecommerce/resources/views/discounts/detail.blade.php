<div class="discount @if ($item->isExpired()) is-discount-disabled @endif">
    @if ($item->isExpired())
        <span class="discount-expired show">{{ trans('plugins/ecommerce::discount.expired') }}</span>
    @endif
    <div class="discount-inner">
        <p class="discount-code"> @if ($item->type === 'coupon') <span class="text-uppercase">{{ trans('plugins/ecommerce::discount.coupon_code') }}</span>: <b>{{ $item->code }}</b> @else <span class="text-uppercase">{{ trans('plugins/ecommerce::discount.discount_promotion') }}</span>: {{ $item->title }} @endif</p>
        <p class="discount-desc">
            {!! get_discount_description($item) !!}
        </p>
        @if ($item->type === 'coupon')
            <p class="@if (!$item->isExpired()) discount-text-color @else discount-desc @endif">({{ trans('plugins/ecommerce::discount.coupon_code') }} <b>@if ($item->can_use_with_promotion) {{ trans('plugins/ecommerce::discount.can') }} @else {{ trans('plugins/ecommerce::discount.cannot')  }} @endif</b> {{ trans('plugins/ecommerce::discount.use_with_promotion') }}).</p>
        @endif
    </div>
</div>
