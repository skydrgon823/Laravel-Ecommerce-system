<section class="list-products products-archive">
    <ul>
    @if ($products->count() > 0)
        @foreach ($products as $product)
            <li>
                <div class="product-item product-loop">
                    <img src="{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" class="product-item-thumb">
                    <h3>{{ $product->name }}</h3>
                    <span class="price">
                        {!! the_product_price($product) !!}
                    </span>
                    <div class="product-action">
                        <a data-quantity = '1' data-product = '{{ $product->id }}' href="javascript: void(0);" class="btn btn-info">{{ __('Add to cart') }}</a>
                    </div>
                </div>
            </li>
            @endforeach
    @endif
    </ul>
</section>
