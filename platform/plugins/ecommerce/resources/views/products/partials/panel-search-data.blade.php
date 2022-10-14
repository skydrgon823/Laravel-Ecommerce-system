<div class="panel-body">
    <div class="list-search-data">
        <ul class="clearfix">
            @if (!$availableProducts->isEmpty())
                @foreach($availableProducts as $availableProduct)
                    <li class="@if (!$includeVariation) selectable-item @endif" @if (!$includeVariation) data-name="{{ $availableProduct->name }}"  data-image="{{ RvMedia::getImageUrl($availableProduct->image, 'thumb', false, RvMedia::getDefaultImage()) }}" data-id="{{ $availableProduct->id }}" data-url="{{ route('products.edit', $availableProduct->id) }}" data-price="{{ $availableProduct->price }}" @endif>
                        <div class="wrap-img inline_block vertical-align-t float-start"><img class="thumb-image" src="{{ RvMedia::getImageUrl($availableProduct->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $availableProduct->name }}"></div>
                        <label class="inline_block ml10 mt10 ws-nm" style="width:calc(100% - 50px);">{{ $availableProduct->name }}</label>
                        @if ($includeVariation)
                            <div class="clear"></div>
                            <ul>
                                @foreach($availableProduct->variations as $variation)
                                    <li class="clearfix product-variant selectable-item" data-name="{{ $availableProduct->name }}"  data-image="{{ RvMedia::getImageUrl($variation->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}" data-id="{{ $variation->product->id }}" data-url="{{ route('products.edit', $availableProduct->id) }}" data-price="{{ $availableProduct->price }}">
                                        <a href="#" class="color_green float-start">
                                            <span>
                                                @foreach($variation->variationItems as $variationItem)
                                                    {{ $variationItem->attribute->title }}
                                                    @if (!$loop->last)
                                                        /
                                                    @endif
                                                @endforeach
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @else
                <li>
                    <p>{{ trans('plugins/ecommerce::products.form.no_results') }}</p>
                </li>
            @endif
        </ul>
    </div>
</div>

@if ($availableProducts->hasPages())
    <div class="panel-footer">
        <div class="btn-group float-end">
            {!! $availableProducts->links() !!}
        </div>
        <div class="clearfix"></div>
    </div>
@endif
