@if ($products->count())
    <div class="search_header__prs fwsb cd">
        <span class="h_results">{{ __('Search Results:') }}</span>
    </div>
    <div class="search_header__content mini_cart_content fixcl-scroll widget">
        <div class="fixcl-scroll-content product_list_widget">
            <div class="js_prs_search">

                @foreach($products as $product)
                    <div class="row mb__10 pb__10">
                        <div class="col widget_img_pr">
                            <a class="d-block pr oh" href="{{ $product->url }}"><img src="data:image/svg+xml,%3Csvg%20viewBox%3D%220%200%20768%20768%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3C%2Fsvg%3E" class="w__100 lz_op_ef lazyload" alt="cru thermos jug" data-src="{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}" width="80" height="80"></a>
                        </div>
                        <div class="col widget_if_pr"><a class="product-title d-block" href="{{ $product->url }}">{{ $product->name }}</a>
                            @if ($product->front_sale_price !== $product->price)
                                <del>{{ format_price($product->price_with_taxes) }}</del>
                            @endif
                            <ins>{{ format_price($product->front_sale_price_with_taxes) }}</ins>
                            @if ($product->front_sale_price !== $product->price)
                                <span class="onsale nt_label">{{ get_sale_percentage($product->price, $product->front_sale_price) }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('public.products') }}?q={{ $query }}" class="btn fwsb detail_link">{{ __('View All') }}
                    <i class="las la-arrow-right fs__18"></i>
                </a>
            </div>
        </div>
    </div>
@else
    <div class="text-center">{{ __('No products found.') }}</a></div>
@endif
