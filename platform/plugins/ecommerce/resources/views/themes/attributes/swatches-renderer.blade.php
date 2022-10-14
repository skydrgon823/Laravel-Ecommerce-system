@php
    $key = mt_rand();
@endphp
<div class="product-attributes" data-target="{{ route('public.web.get-variation-by-attributes', $product->id) }}">
     @php
         $variationInfo = $productVariationsInfo;
         $variationNextIds = [];
    @endphp
    <ul>
        @foreach($attributeSets as $set)
            @if (!$loop->first)
                @php
                    $variationInfo = $productVariationsInfo->where('attribute_set_id', $set->id)->whereIn('variation_id', $variationNextIds);
                @endphp
            @endif
            @if (view()->exists('plugins/ecommerce::themes.attributes._layouts.' . $set->display_layout))
                @include('plugins/ecommerce::themes.attributes._layouts.' . $set->display_layout, compact('selected'))
            @else
                @include('plugins/ecommerce::themes.attributes._layouts.dropdown', compact('selected'))
            @endif
            @php
                [$variationNextIds] = handle_next_attributes_in_product(
                    $attributes->where('attribute_set_id', $set->id),
                    $productVariationsInfo,
                    $set->id,
                    $selected->pluck('id')->toArray(),
                    $loop->index,
                    $variationNextIds);
            @endphp
        @endforeach
    </ul>
</div>
