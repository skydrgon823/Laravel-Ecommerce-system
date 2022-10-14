<div class="note note-warning">
    @if ($flashSale)
        <p>{!! BaseHelper::clean(trans('plugins/ecommerce::products.product_price_flash_sale_warning', ['name' => $flashSale->name, 'price' => format_price($data->front_sale_price)])) !!}</p>
    @endif

    @if ($discount)
        <p>{!! BaseHelper::clean(trans('plugins/ecommerce::products.product_price_discount_warning', ['name' => $discount->title, 'price' => format_price($data->front_sale_price)])) !!}</p>
    @endif
</div>
