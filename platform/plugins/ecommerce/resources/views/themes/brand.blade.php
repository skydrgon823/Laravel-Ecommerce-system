@php
    //get all product of category
    $products = get_product_by_brand([
        'brand_id' => $brand->id,
        'condition' => [
            'ec_products.status' => \Botble\Base\Enums\BaseStatusEnum::PUBLISHED,
            'ec_products.is_variation' => 0,
        ],
        'order_by' => [
            'ec_products.order' => 'ASC',
            'ec_products.created_at' => 'DESC',
        ],

        'paginate' => [
            'per_page' => 20,
            'current_paged' => 1
        ],
   ]);
@endphp
<div class="content-page">
    <div class="container">
        <!-- End Bread Crumb -->
        <div class="boxed-slider radius">
            <div class="wrap-item" data-itemscustom="[[0,1]]" data-pagination="false" data-navigation="true">
                <div class="banner-shop">
                    <div class="banner-shop-thumb">
                        <a href="#"><img alt="" src="{{ Theme::asset()->url('images/banner3.jpg') }}"></a>
                    </div>
                    <div class="banner-shop-info text-start">
                        <h2>{{ __('Fashion Collection') }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-grid-boxed">
            <div class="sort-pagi-bar clearfix">
                <div class="view-type float-start">
                    <a href="#" class="grid-view active"></a>
                </div>
                <div class="sort-paginav float-end">

                    <div class="show-bar select-box">
                        <label>{{ __('Show') }}:</label>
                        <select>
                            <option value="">20</option>
                        </select>
                    </div>
                    <div class="pagi-bar">
                        {!! $products->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Sort PagiBar -->
            <div class="grid-pro-color">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="item-pro-color">
                                <div class="product-thumb">
                                    <a href="{{ $product->url }}" class="product-thumb-link">
                                        @foreach ($product->images as $image)
                                            <img data-color="black" @if ($loop->first) class="active" @endif src="{{ RvMedia::getImageUrl($image, 'thumb') }}" alt="">
                                        @endforeach
                                    </a>

                                </div>
                                <div class="product-info">
                                    <h3 class="product-title"><a href="{{ $product->url }}">{{ $product->name }}</a></h3>
                                    <div class="product-price">
                                        @if ( $product->front_sale_price )  <del><span>{{ format_price($product->front_sale_price) }}</span></del> @endif
                                        <ins><span>{{ format_price ($product->price) }}</span></ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="pagi-bar bottom">
                    {!! $products->links() !!}
                </div>
            </div>
            <!-- End List Pro color -->
        </div>
    </div>
</div>
