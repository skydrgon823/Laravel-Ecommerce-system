@extends('plugins/ecommerce::orders.master')
@section('title')
    {{ __('Checkout') }}
@stop
@section('content')

    @if (Cart::instance('cart')->count() > 0)
        <link rel="stylesheet" href="{{ asset('vendor/core/plugins/payment/css/payment.css') }}?v=1.1.0">
        <script src="{{ asset('vendor/core/plugins/payment/js/payment.js') }}?v=1.1.0"></script>

        {!! apply_filters(PAYMENT_FILTER_HEADER_ASSETS, null) !!}

        {!! Form::open(['route' => ['public.checkout.process', $token], 'class' => 'checkout-form payment-checkout-form', 'id' => 'checkout-form']) !!}
        <input type="hidden" name="checkout-token" id="checkout-token" value="{{ $token }}">

        <div class="container" id="main-checkout-product-info">
            <div class="row">
                <div class="order-1 order-md-2 col-lg-5 col-md-6 right">
                    <div class="d-block d-sm-none">
                        @include('plugins/ecommerce::orders.partials.logo')
                    </div>
                    <div id="cart-item" class="position-relative">

                        <div class="payment-info-loading" style="display: none;">
                            <div class="payment-info-loading-content">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>

                        <!---------------------- RENDER PRODUCTS IN HERE ---------------- -->
                        {!! apply_filters(RENDER_PRODUCTS_IN_CHECKOUT_PAGE, $products) !!}

                        <div class="mt-2 p-2">
                            <div class="row">
                                <div class="col-6">
                                    <p>{{ __('Subtotal') }}:</p>
                                </div>
                                <div class="col-6">
                                    <p class="price-text sub-total-text text-end"> {{ format_price(Cart::instance('cart')->rawSubTotal()) }} </p>
                                </div>
                            </div>
                            @if (session('applied_coupon_code'))
                                <div class="row coupon-information">
                                    <div class="col-6">
                                        <p>{{ __('Coupon code') }}:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="price-text coupon-code-text"> {{ session('applied_coupon_code') }} </p>
                                    </div>
                                </div>
                            @endif
                            @if ($couponDiscountAmount > 0)
                                <div class="row price discount-amount">
                                    <div class="col-6">
                                        <p>{{ __('Coupon code discount amount') }}:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="price-text total-discount-amount-text"> {{ format_price($couponDiscountAmount) }} </p>
                                    </div>
                                </div>
                            @endif
                            @if ($promotionDiscountAmount > 0)
                                <div class="row">
                                    <div class="col-6">
                                        <p>{{ __('Promotion discount amount') }}:</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="price-text"> {{ format_price($promotionDiscountAmount) }} </p>
                                    </div>
                                </div>
                            @endif
                            @if (!empty($shipping) && Arr::get($sessionCheckoutData, 'is_available_shipping', true))
                                <div class="row">
                                    <div class="col-6">
                                        <p>{{ __('Shipping fee') }}:</p>
                                    </div>
                                    <div class="col-6 float-end">
                                        <p class="price-text shipping-price-text">{{ format_price($shippingAmount) }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (EcommerceHelper::isTaxEnabled())
                                <div class="row">
                                    <div class="col-6">
                                        <p>{{ __('Tax') }}:</p>
                                    </div>
                                    <div class="col-6 float-end">
                                        <p class="price-text tax-price-text">{{ format_price(Cart::instance('cart')->rawTax()) }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-6">
                                    <p><strong>{{ __('Total') }}</strong>:</p>
                                </div>
                                <div class="col-6 float-end">
                                    <p class="total-text raw-total-text"
                                       data-price="{{ format_price(Cart::instance('cart')->rawTotal(), null, true) }}"> {{ ($promotionDiscountAmount + $couponDiscountAmount - $shippingAmount) > Cart::instance('cart')->rawTotal() ? format_price(0) : format_price(Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount + $shippingAmount) }} </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mt-3 mb-5">
                        @include('plugins/ecommerce::themes.discounts.partials.form')
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 left">
                    <div class="d-none d-sm-block">
                        @include('plugins/ecommerce::orders.partials.logo')
                    </div>
                    <div class="form-checkout">
                        <div>
                            <h5 class="checkout-payment-title">{{ __('Shipping information') }}</h5>
                            <input type="hidden" value="{{ route('public.checkout.save-information', $token) }}" id="save-shipping-information-url">
                            @include('plugins/ecommerce::orders.partials.address-form', compact('sessionCheckoutData'))
                        </div>
                        <br>

                        @if (EcommerceHelper::isBillingAddressEnabled())
                            <div>
                                <h5 class="checkout-payment-title">{{ __('Billing information') }}</h5>
                                @include('plugins/ecommerce::orders.partials.billing-address-form', compact('sessionCheckoutData'))
                            </div>
                            <br>
                        @endif

                        @if (!is_plugin_active('marketplace'))
                            @if (Arr::get($sessionCheckoutData, 'is_available_shipping', true))
                                <div id="shipping-method-wrapper">
                                    <h5 class="checkout-payment-title">{{ __('Shipping method') }}</h5>
                                    <div class="shipping-info-loading" style="display: none;">
                                        <div class="shipping-info-loading-content">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </div>
                                    </div>
                                    @if (!empty($shipping))
                                        <div class="payment-checkout-form">
                                            <input type="hidden" name="shipping_option" value="{{ old('shipping_option', $defaultShippingOption) }}">
                                            <ul class="list-group list_payment_method">
                                                @foreach ($shipping as $shippingKey => $shippingItem)
                                                    @foreach($shippingItem as $subShippingKey => $subShippingItem)
                                                        @include('plugins/ecommerce::orders.partials.shipping-option', [
                                                            'defaultShippingMethod' => $defaultShippingMethod,
                                                            'defaultShippingOption' => $defaultShippingOption,
                                                            'shippingOption'        => $subShippingKey,
                                                            'shippingItem'          => $subShippingItem,
                                                        ])
                                                    @endforeach
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <p>{{ __('No shipping methods available!') }}</p>
                                    @endif
                                </div>
                                <br>
                            @else
                                {{-- Can render text to show for customer --}}
                            @endif
                        @endif

                        <div class="position-relative">
                            <div class="payment-info-loading" style="display: none;">
                                <div class="payment-info-loading-content">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            <h5 class="checkout-payment-title">{{ __('Payment method') }}</h5>
                            <input type="hidden" name="amount" value="{{ ($promotionDiscountAmount + $couponDiscountAmount - $shippingAmount) > Cart::instance('cart')->rawTotal() ? 0 : format_price(Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount + $shippingAmount, null, true) }}">
                            <input type="hidden" name="currency" value="{{ strtoupper(get_application_currency()->title) }}">
                            {!! apply_filters(PAYMENT_FILTER_PAYMENT_PARAMETERS, null) !!}
                            <ul class="list-group list_payment_method">

                                {!! apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, ['amount' => ($promotionDiscountAmount + $couponDiscountAmount - $shippingAmount) > Cart::instance('cart')->rawTotal() ? 0 : format_price(Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount + $shippingAmount, null, true), 'currency' => strtoupper(get_application_currency()->title), 'name' => null]) !!}

                                @if (setting('payment_cod_status') == 1)
                                    <li class="list-group-item">
                                        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_cod"
                                               @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::COD) checked @endif
                                               value="cod" data-bs-toggle="collapse" data-bs-target=".payment_cod_wrap" data-parent=".list_payment_method">
                                        <label for="payment_cod" class="text-start">{{ setting('payment_cod_name', trans('plugins/payment::payment.payment_via_cod')) }}</label>
                                        <div class="payment_cod_wrap payment_collapse_wrap collapse @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::COD) show @endif" style="padding: 15px 0;">
                                            {!! BaseHelper::clean(setting('payment_cod_description')) !!}

                                            @php $minimumOrderAmount = setting('payment_cod_minimum_amount', 0); @endphp
                                            @if ($minimumOrderAmount > Cart::instance('cart')->rawSubTotal())
                                                <div class="alert alert-warning" style="margin-top: 15px;">
                                                    {{ __('Minimum order amount to use COD (Cash On Delivery) payment method is :amount, you need to buy more :more to place an order!', ['amount' => format_price($minimumOrderAmount), 'more' => format_price($minimumOrderAmount - Cart::instance('cart')->rawSubTotal())]) }}
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endif

                                @if (setting('payment_bank_transfer_status') == 1)
                                    <li class="list-group-item">
                                        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_bank_transfer"
                                               @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) checked @endif
                                               value="bank_transfer" data-bs-toggle="collapse" data-bs-target=".payment_bank_transfer_wrap" data-parent=".list_payment_method">
                                        <label for="payment_bank_transfer" class="text-start">{{ setting('payment_bank_transfer_name', trans('plugins/payment::payment.payment_via_bank_transfer')) }}</label>
                                        <div class="payment_bank_transfer_wrap payment_collapse_wrap collapse @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) show @endif" style="padding: 15px 0;">
                                            {!! BaseHelper::clean(setting('payment_bank_transfer_description')) !!}
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <br>

                        <div class="form-group mb-3 @if ($errors->has('description')) has-error @endif">
                            <label for="description" class="control-label">{{ __('Order notes') }}</label>
                            <br>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="{{ __('Notes about your order, e.g. special notes for delivery.') }}">{{ old('description') }}</textarea>
                            {!! Form::error('description', $errors) !!}
                        </div>

                        @if (EcommerceHelper::getMinimumOrderAmount() > Cart::instance('cart')->rawSubTotal())
                            <div class="alert alert-warning">
                                {{ __('Minimum order amount is :amount, you need to buy more :more to place an order!', ['amount' => format_price(EcommerceHelper::getMinimumOrderAmount()), 'more' => format_price(EcommerceHelper::getMinimumOrderAmount() - Cart::instance('cart')->rawSubTotal())]) }}
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-6 d-none d-md-block" style="line-height: 53px">
                                    <a class="text-info" href="{{ route('public.cart') }}"><i class="fas fa-long-arrow-alt-left"></i> <span class="d-inline-block back-to-cart">{{ __('Back to cart') }}</span></a>
                                </div>
                                <div class="col-md-6 checkout-button-group">
                                    <button type="submit" @if (EcommerceHelper::getMinimumOrderAmount() > Cart::instance('cart')->rawSubTotal()) disabled @endif class="btn payment-checkout-btn payment-checkout-btn-step float-end" data-processing-text="{{ __('Processing. Please wait...') }}" data-error-header="{{ __('Error') }}">
                                        {{ __('Checkout') }}
                                    </button>
                                </div>
                            </div>
                            <div class="d-block d-md-none back-to-cart-button-group">
                                <a class="text-info" href="{{ route('public.cart') }}"><i class="fas fa-long-arrow-alt-left"></i> <span class="d-inline-block">{{ __('Back to cart') }}</span></a>
                            </div>
                        </div>

                    </div> <!-- /form checkout -->
                </div>
            </div>
        </div>

        {!! apply_filters(PAYMENT_FILTER_FOOTER_ASSETS, null) !!}
    @else
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning my-5">
                        <span>{!! __('No products in cart. :link!', ['link' => Html::link(route('public.index'), __('Back to shopping'))]) !!}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop
