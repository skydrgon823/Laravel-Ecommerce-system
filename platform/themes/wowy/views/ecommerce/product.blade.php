@php
    $layout = MetaBox::getMetaData($product, 'layout', true);
    $layout = ($layout && in_array($layout, array_keys(get_product_single_layouts()))) ? $layout : 'product-right-sidebar';
    Theme::layout($layout);

    Theme::asset()->usePath()->add('lightGallery-css', 'plugins/lightGallery/css/lightgallery.min.css');
    Theme::asset()->container('footer')->usePath()
        ->add('lightGallery-js', 'plugins/lightGallery/js/lightgallery.min.js', ['jquery']);
@endphp

<div class="product-detail accordion-detail">
    <div class="row mb-50">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="detail-gallery">
                <!-- MAIN SLIDES -->
                <div class="product-image-slider">
                    @foreach ($productImages as $img)
                        <figure class="border-radius-10">
                            <a href="{{ RvMedia::getImageUrl($img) }}">
                                <img src="{{ RvMedia::getImageUrl($img, 'medium') }}" alt="{{ $product->name }}">
                            </a>
                        </figure>
                    @endforeach
                </div>
                <!-- THUMBNAILS -->
                <div class="slider-nav-thumbnails pl-15 pr-15">
                    @foreach ($productImages as $img)
                        <div><img src="{{ RvMedia::getImageUrl($img, 'thumb') }}" alt="{{ $product->name }}"></div>
                    @endforeach
                </div>
            </div>
            <!-- End Gallery -->
            <div class="single-social-share clearfix mt-50 mb-15">
                <p class="mb-15 mt-30 font-sm"> <i class="fa fa-share-alt mr-5"></i> <span class="d-inline-block">{{ __('Share this') }}</span></p>
                <div class="mobile-social-icon wow fadeIn  mb-sm-5 mb-md-0 animated">
                    <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a class="twitter" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ strip_tags(strip_tags(SeoHelper::getDescription())) }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&summary={{ rawurldecode(strip_tags(SeoHelper::getDescription())) }}" target="_blank"><i class="fab 
fa-linkedin"></i></a>
                </div>
            </div>
            <a class="mail-to-friend font-sm color-grey" href="mailto:someone@example.com?subject={{ __('Buy') }} {{ $product->name }}&body={{ __('Buy this one: :link', ['link' => $product->url]) }}"><i class="far fa-envelope"></i> {{ __('Email to a Friend') }}</a>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="detail-info">
                <h2 class="title-detail">{{ $product->name }}</h2>
                <div class="product-detail-rating">
                    @if ($product->brand->id)
                        <div class="pro-details-brand">
                            <span class="d-inline-block">{{ __('Brands') }}:</span> <a href="{{ $product->brand->url }}">{{ $product->brand->name }}</a>
                        </div>
                    @endif

                    @if (EcommerceHelper::isReviewEnabled())
                        <div class="product-rate-cover text-end">
                            <div class="rating_wrap">
                                <div class="rating">
                                    <div class="product_rate" style="width: {{ $product->reviews_avg * 20 }}%"></div>
                                </div>
                                <span class="rating_num">({{ __(':count reviews', ['count' => $product->reviews_count]) }})</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="clearfix product-price-cover">
                    <div class="product-price primary-color float-left">
                        <ins><span class="text-brand">{{ format_price($product->front_sale_price_with_taxes) }}</span></ins>
                        @if ($product->front_sale_price !== $product->price)
                            <ins><span class="old-price font-md ml-15">{{ format_price($product->price_with_taxes) }}</span></ins>
                            <span class="save-price font-md color3 ml-15"><span class="percentage-off d-inline-block">{{ get_sale_percentage($product->price, $product->front_sale_price) }}</span> <span class="d-inline-block">{{ __('Off') }}</span></span>
                        @endif
                    </div>
                </div>
                <div class="bt-1 border-color-1 mt-15 mb-15"></div>
                <div class="short-desc mb-30">
                    {!! apply_filters('ecommerce_before_product_description', null, $product) !!}
                    {!! BaseHelper::clean($product->description) !!}
                    {!! apply_filters('ecommerce_after_product_description', null, $product) !!}
                </div>

                @if ($product->variations()->count() > 0)
                    <div class="pr_switch_wrap">
                        {!! render_product_swatches($product, [
                            'selected' => $selectedAttrs,
                            'view'     => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer'
                        ]) !!}
                    </div>
                    <div class="number-items-available" style="@if (!$product->isOutOfStock()) display: none; @endif margin-bottom: 10px;">
                        @if ($product->isOutOfStock())
                            <span class="text-danger">({{ __('Out of stock') }})</span>
                        @endif
                    </div>
                @endif

                <div class="bt-1 border-color-1 mt-30 mb-30"></div>
                <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}">
                    @csrf
                    {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null, $product) !!}
                    <input type="hidden" name="id" class="hidden-product-id" value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}"/>
                    <div class="detail-extralink">
                        @if (EcommerceHelper::isCartEnabled())
                            <div class="detail-qty border radius">
                                <a href="#" class="qty-down"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
                                <input type="number" min="1" value="1" name="qty" class="qty-val qty-input" />
                                <a href="#" class="qty-up"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
                            </div>
                        @endif

                        <div class="product-extra-link2 @if (EcommerceHelper::isQuickBuyButtonEnabled()) has-buy-now-button @endif">
                            @if (EcommerceHelper::isCartEnabled())
                                <button type="submit" class="button button-add-to-cart @if ($product->isOutOfStock()) btn-disabled @endif" type="submit" @if ($product->isOutOfStock()) disabled @endif>{{ __('Add to cart') }}</button>
                                @if (EcommerceHelper::isQuickBuyButtonEnabled())
                                    <button class="button button-buy-now @if ($product->isOutOfStock()) btn-disabled @endif" type="submit" name="checkout" @if ($product->isOutOfStock()) disabled @endif>{{ __('Buy Now') }}</button>
                                @endif
                            @endif

                            @if (EcommerceHelper::isWishlistEnabled())
                                <a aria-label="{{ __('Add To Wishlist') }}" class="action-btn hover-up js-add-to-wishlist-button" data-url="{{ route('public.wishlist.add', $product->id) }}" href="#"><i class="far fa-heart"></i></a>
                            @endif
                            @if (EcommerceHelper::isCompareEnabled())
                                <a aria-label="{{ __('Add To Compare') }}" href="#" class="action-btn hover-up js-add-to-compare-button" data-url="{{ route('public.compare.add', $product->id) }}"><i class="far fa-exchange-alt"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
                <ul class="product-meta font-xs color-grey mt-50">
                    @if ($product->sku)
                        <li class="mb-5"><span class="d-inline-block" id="product-sku">{{ __('SKU') }}</span>: <span>{{ $product->sku }}</span></li>
                    @endif

                    @if ($product->categories->count())
                        <li class="mb-5"><span class="d-inline-block">{{ __('Categories') }}:</span>
                        @foreach($product->categories as $category)
                            <a href="{{ $category->url }}" title="{{ $category->name }}">{{ $category->name }}</a>@if (!$loop->last),@endif
                        @endforeach
                    </li>
                    @endif
                    @if ($product->tags->count())
                        <li class="mb-5"><span class="d-inline-block">{{ __('Tags') }}:</span>
                        @foreach($product->tags as $tag)
                            <a href="{{ $tag->url }}" rel="tag" title="{{ $tag->name }}">{{ $tag->name }}</a>@if (!$loop->last),@endif
                        @endforeach
                        </li>
                    @endif

                    <li><span class="d-inline-block">{{ __('Availability') }}:</span> <span class="in-stock text-success ml-5">{!! BaseHelper::clean($product->stock_status_html) !!}</span></li>
                </ul>
            </div>
            <!-- Detail Info -->

        </div>
    </div>
    <div class="tab-style3">
        <ul class="nav nav-tabs text-uppercase">
            <li class="nav-item">
                <a class="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description">{{ __('Description') }}</a>
            </li>
            @if (EcommerceHelper::isReviewEnabled())
                <li class="nav-item">
                    <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews">{{ __('Reviews') }} ({{ $product->reviews_count }})</a>
                </li>
            @endif
            @if (is_plugin_active('faq'))
                @if (count($product->faq_items) > 0)
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-faq">{{ __('Questions and Answers') }}</a>
                    </li>
                @endif
            @endif
        </ul>
        <div class="tab-content shop_info_tab entry-main-content">
            <div class="tab-pane fade show active" id="Description">
                {!! BaseHelper::clean($product->content) !!}
                @if (theme_option('facebook_comment_enabled_in_product', 'yes') == 'yes')
                    <br />
                    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, Theme::partial('comments')) !!}
                @endif
            </div>

            @if (is_plugin_active('faq') && count($product->faq_items) > 0)
                <div class="tab-pane fade faqs-list" id="tab-faq">
                    <div class="accordion" id="faq-accordion">
                        @foreach($product->faq_items as $faq)
                            <div class="card">
                                <div class="card-header" id="heading-faq-{{ $loop->index }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left @if (!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-{{ $loop->index }}" aria-expanded="true" aria-controls="collapse-faq-{{ $loop->index }}">
                                            {!! BaseHelper::clean($faq[0]['value']) !!}
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse-faq-{{ $loop->index }}" class="collapse @if ($loop->first) show @endif" aria-labelledby="heading-faq-{{ $loop->index }}" data-parent="#faq-accordion">
                                    <div class="card-body">
                                        {!! BaseHelper::clean($faq[1]['value']) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (EcommerceHelper::isReviewEnabled())
            <div class="tab-pane fade" id="Reviews">
                @if ($product->reviews_count > 0)
                    @if (count($product->review_images))
                        <div class="my-3">
                            <h4>{{ __('Images from customer (:count)', ['count' => count($product->review_images)]) }}</h4>
                            <div class="block--review">
                                <div class="block__images row m-0 block__images_total">
                                    @foreach ($product->review_images as $img)
                                        <a href="{{ RvMedia::getImageUrl($img) }}" class="col-lg-1 col-sm-2 col-3 more-review-images @if ($loop->iteration > 6) d-none @endif">
                                            <div class="border position-relative rounded">
                                                <img src="{{ RvMedia::getImageUrl($img, 'thumb') }}" alt="{{ $product->name }}" class="img-responsive rounded h-100">
                                                @if ($loop->iteration == 6 && (count($product->review_images) - $loop->iteration > 0))
                                                    <span>+{{ count($product->review_images) - $loop->iteration }}</span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="comments-area">
                        <div class="row">
                            <div class="col-lg-8 block--product-reviews" id="product-reviews">
                                <h4 class="mb-30">{{ __('Customer questions & answers') }}</h4>
                                <product-reviews-component url="{{ route('public.ajax.product-reviews', $product->id) }}"></product-reviews-component>
                            </div>
                            <div class="col-lg-4">
                                <h4 class="mb-30">{{ __('Customer reviews') }}</h4>
                                <div class="d-flex mb-30">
                                    <div class="rating_wrap">
                                        <div class="rating">
                                            <div class="product_rate" style="width: {{ $product->reviews_avg * 20 }}%"></div>
                                        </div>
                                        <span class="rating_num">({{ __(':avg out of 5', ['avg' => number_format($product->reviews_avg, 2)]) }})</span>
                                    </div>
                                </div>

                                @foreach (EcommerceHelper::getReviewsGroupedByProductId($product->id, $product->reviews_count) as $item)
                                    <div class="progress">
                                        <span>{{ __(':number star', ['number' => $item['star']]) }}</span>

                                        <div class="progress-bar" role="progressbar" style="width: {{ $item['percent'] }}%;" aria-valuenow="{{ $item['percent'] }}" aria-valuemin="0" aria-valuemax="100">{{ $item['percent'] }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <p>{{ __('No reviews!') }}</p>
                @endif
                <!--comment form-->
                <div class="comment-form" @if (!$product->reviews_count) style="border: none" @endif>
                    <h4 class="mb-15">{{ __('Add a review') }}</h4>
                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            {!! Form::open(['route' => 'public.reviews.create', 'method' => 'post', 'class' => 'form-contact comment_form form-review-product', 'files' => true]) !!}
                                @if (!auth('customer')->check())
                                    <p class="text-danger">{{ __('Please') }} <a href="{{ route('customer.login') }}">{{ __('login') }}</a> {{ __('to write review!') }}</p>
                                @endif
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="form-group">
                                    <label>{{ __('Quality') }}</label>
                                    <div class="rate">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="star" value="{{ $i }}" @if ($i == 5) checked @endif>
                                            <label for="star{{ $i }}" title="text">{{ __(':number star', ['number' => $i]) }}</label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="{{ __('Write Comment') }}" @if (!auth('customer')->check()) disabled @endif></textarea>
                                </div>

                                <div class="form-group">
                                    <script type="text/x-custom-template" id="review-image-template">
                                        <span class="image-viewer__item" data-id="__id__">
                                            <img src="{{ RvMedia::getDefaultImage() }}" alt="Preview" class="img-responsive d-block">
                                            <span class="image-viewer__icon-remove">
                                                <i class="far fa-times"></i>
                                            </span>
                                        </span>
                                    </script>
                                    <div class="image-upload__viewer d-flex">
                                        <div class="image-viewer__list position-relative">
                                            <div class="image-upload__uploader-container">
                                                <div class="d-table">
                                                    <div class="image-upload__uploader">
                                                        <i class="far fa-image image-upload__icon"></i>
                                                        <div class="image-upload__text">{{ __('Upload photos') }}</div>
                                                        <input type="file"
                                                               name="images[]"
                                                               data-max-files="{{ EcommerceHelper::reviewMaxFileNumber() }}"
                                                               class="image-upload__file-input"
                                                               accept="image/png,image/jpeg,image/jpg"
                                                               multiple="multiple"
                                                               data-max-size="{{ EcommerceHelper::reviewMaxFileSize(true) }}"
                                                               data-max-size-message="{{ trans('validation.max.file', ['attribute' => '__attribute__', 'max' => '__max__']) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="loading">
                                                <div class="half-circle-spinner">
                                                    <div class="circle circle-1"></div>
                                                    <div class="circle circle-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="help-block d-inline-block">
                                            {{ __('You can upload up to :total photos, each photo maximum size is :max kilobytes', [
                                                'total' => EcommerceHelper::reviewMaxFileNumber(),
                                                'max'   => EcommerceHelper::reviewMaxFileSize(true),
                                            ]) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="button button-contactForm" @if (!auth('customer')->check()) disabled @endif>{{ __('Submit Review') }}</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

            </div>
            @endif
        </div>
    </div>

    @php
        $crossSellProducts = get_cross_sale_products($product, $layout == 'product-full-width' ? 4 : 3);
    @endphp
    @if (count($crossSellProducts) > 0)
        <div class="row mt-60">
            <div class="col-12">
                <h3 class="section-title style-1 mb-30">{{ __('You may also like') }}</h3>
            </div>
            @foreach($crossSellProducts as $crossProduct)
                <div class="col-lg-{{ 12 / ($layout == 'product-full-width' ? 4 : 3) }} col-md-4 col-12 col-sm-6">
                    @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.product-item', ['product' => $crossProduct])
                </div>
            @endforeach
        </div>
    @endif

    <div class="row mt-60" id="related-products">
        <div class="col-12">
            <h3 class="section-title style-1 mb-30">{{ __('Related products') }}</h3>
        </div>
        <related-products-component url="{{ route('public.ajax.related-products', $product->id) }}" :limit="{{ $layout == 'product-full-width' ? 4 : 3 }}"></related-products-component>
    </div>
</div>
