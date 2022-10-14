<link rel="stylesheet" href="{{ asset('vendor/core/plugins/payment/css/payment.css') }}?v=1.0.4">
<script src="{{ asset('vendor/core/plugins/payment/js/payment.js') }}?v=1.0.4"></script>

{!! apply_filters(PAYMENT_FILTER_HEADER_ASSETS, null) !!}

<div class="checkout-wrapper">
    <div>
        <form action="{{ $action }}" method="post" class="payment-checkout-form">
            @csrf
            <input type="hidden" name="name" value="{{ $name }}">
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="currency" value="{{ $currency }}">
            @if (isset($returnUrl))
                <input type="hidden" name="return_url" value="{{ $returnUrl }}">
            @endif
            @if (isset($callbackUrl))
                <input type="hidden" name="callback_url" value="{{ $callbackUrl }}">
            @endif
            {!! apply_filters(PAYMENT_FILTER_PAYMENT_PARAMETERS, null) !!}
            <ul class="list-group list_payment_method">

                {!! apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, compact('name', 'amount', 'currency')) !!}

                @if (setting('payment_cod_status') == 1)
                    <li class="list-group-item">
                        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_cod"
                               @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::COD) checked @endif
                               value="cod" data-bs-toggle="collapse" data-bs-target=".payment_cod_wrap" data-toggle="collapse" data-target=".payment_cod_wrap" data-parent=".list_payment_method">
                        <label for="payment_cod" class="text-start">{{ setting('payment_cod_name', trans('plugins/payment::payment.payment_via_cod')) }}</label>
                        <div class="payment_cod_wrap payment_collapse_wrap collapse @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::COD) show @endif" style="padding: 15px 0;">
                            <p>{!! BaseHelper::clean(setting('payment_cod_description')) !!}</p>
                        </div>
                    </li>
                @endif

                @if (setting('payment_bank_transfer_status') == 1)
                    <li class="list-group-item">
                        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_bank_transfer"
                               @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) checked @endif
                               value="bank_transfer" data-bs-toggle="collapse" data-bs-target=".payment_bank_transfer_wrap" data-toggle="collapse" data-target=".payment_bank_transfer_wrap" data-parent=".list_payment_method">
                        <label for="payment_bank_transfer" class="text-start">{{ setting('payment_bank_transfer_name', trans('plugins/payment::payment.payment_via_bank_transfer')) }}</label>
                        <div class="payment_bank_transfer_wrap payment_collapse_wrap collapse @if ((session('selected_payment_method') ?: setting('default_payment_method')) == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) show @endif" style="padding: 15px 0;">
                            <p>{!! BaseHelper::clean(setting('payment_bank_transfer_description')) !!}</p>
                        </div>
                    </li>
                @endif
            </ul>

            <br>
            <div class="text-center">
                <button class="payment-checkout-btn btn btn-info" data-processing-text="{{ __('Processing. Please wait...') }}" data-error-header="{{ __('Error') }}">{{ __('Checkout') }}</button>
            </div>
        </form>
    </div>
</div>

{!! apply_filters(PAYMENT_FILTER_FOOTER_ASSETS, null) !!}
