<section class="mt-60 mb-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if ($products->total())
                    <div class="table-responsive">
                        <table class="table shopping-summery text-center">
                            <thead>
                                <tr class="main-heading">
                                    <th scope="col" colspan="2">{{ __('Product') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Stock Status') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                    <th scope="col">{{ __('Remove') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td class="image product-thumbnail">
                                            <img alt="{{ $product->name }}" src="{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}">
                                        </td>
                                        <td class="product-des product-name">
                                            <p class="product-name"><a href="{{ $product->url }}">{{ $product->name }}</a></p>
                                        </td>

                                        <td class="price" data-title="{{ __('Price') }}">
                                            <span>{{ format_price($product->front_sale_price_with_taxes) }}</span>
                                            @if ($product->front_sale_price != $product->price)
                                                <small><del>{{ format_price($product->price_with_taxes) }}</del></small>
                                            @endif
                                        </td>

                                        <td class="text-center" data-title="{{ __('Stock Status') }}">
                                            <span class="color3 font-weight-bold">
                                                {!! BaseHelper::clean($product->stock_status_html) !!}
                                            </span>
                                        </td>

                                        <td class="text-right" data-title="{{ __('Action') }}">
                                            <a href="#" class="btn btn-rounded btn-sm add-to-cart-button" data-id="{{ $product->id }}" data-url="{{ route('public.cart.add-to-cart') }}"><i class="far fa-shopping-bag mr-5"></i>{{ __('Add to cart') }}</a>
                                        </td>

                                        <td class="action" data-title="{{ __('Remove') }}">
                                            <a href="#" class="js-remove-from-wishlist-button" data-url="{{ route('public.wishlist.remove', $product->id) }}"><i class="fa fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $products->withQueryString()->links(Theme::getThemeNamespace() . '::partials.custom-pagination') !!}
                @else
                    <p>{{ __('No item in wishlist!') }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
