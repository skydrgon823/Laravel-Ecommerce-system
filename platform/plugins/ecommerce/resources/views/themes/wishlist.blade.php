<div class="title">
    <h2 class="customer-page-title">{{ __('Wishlist') }}</h2>
</div>
<br>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>{{ __('Image') }}</th>
            <th>{{ __('Product') }}</th>
            <th>{{ __('Price') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @if ($products->total())
                @foreach($products as $product)
                    <tr>
                        <td>
                            <img alt="{{ $product->original_product->name }}" width="50" height="70" class="img-fluid" style="max-height: 75px" src="{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}">
                        </td>
                        <td><a href="{{ $product->original_product->url }}">{{ $product->original_product->name }}</a></td>

                        <td>
                            <div class="product__price @if ($product->front_sale_price != $product->price) sale @endif">
                                <span>{{ format_price($product->front_sale_price_with_taxes) }}</span>
                                @if ($product->front_sale_price != $product->price)
                                    <small><del>{{ format_price($product->price_with_taxes) }}</del></small>
                                @endif
                            </div>
                        </td>

                        <td>
                            <a href="{{ route('public.wishlist.remove', $product->id) }}">{{ __('Remove') }}</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">{{ __('No product in wishlist!') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if ($products->total())
    {!! $products->links() !!}
@endif
