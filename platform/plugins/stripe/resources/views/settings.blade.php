@php $stripeStatus = setting('payment_stripe_status'); @endphp
<table class="table payment-method-item">
    <tbody><tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/stripe/images/stripe.svg') }}" alt="stripe">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://stripe.com" target="_blank">Stripe</a>
                    <p>{{ trans('plugins/payment::payment.stripe_description') }}</p>
                </li>
            </ul>
        </td>
    </tr>
    <tr class="bg-white">
        <td colspan="3">
            <div class="float-start" style="margin-top: 5px;">
                <div class="payment-name-label-group @if ($stripeStatus == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label class="ws-nm inline-display method-name-label">{{ setting('payment_stripe_name') }}</label>
                </div>
            </div>
            <div class="float-end">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($stripeStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($stripeStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', STRIPE_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'Stripe']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'Stripe']) }}:</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://dashboard.stripe.com/register" target="_blank">
                                        {{ trans('plugins/payment::payment.service_registration', ['name' => 'Stripe']) }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ trans('plugins/payment::payment.stripe_after_service_registration_msg', ['name' => 'Stripe']) }}</p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ trans('plugins/payment::payment.stripe_enter_client_id_and_secret') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="stripe_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input input-name" name="payment_stripe_name" id="stripe_name" data-counter="400" value="{{ setting('payment_stripe_name', trans('plugins/payment::payment.pay_online_via', ['name' => 'Stripe'])) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="payment_stripe_description">{{ trans('core/base::forms.description') }}</label>
                            <textarea class="next-input" name="payment_stripe_description" id="payment_stripe_description">{{ get_payment_setting('description', 'stripe', __('Payment with Stripe')) }}</textarea>
                        </div>
                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank" href="//www.stripe.com">Stripe</a>:
                        </p>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="stripe_client_id">{{ trans('plugins/payment::payment.stripe_key') }}</label>
                            <input type="text" class="next-input" name="payment_stripe_client_id" id="stripe_client_id" placeholder="pk_*************" value="{{ app()->environment('demo') ? '*******************************' : setting('payment_stripe_client_id') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="stripe_secret">{{ trans('plugins/payment::payment.stripe_secret') }}</label>
                            <div class="input-option">
                                <input type="password" class="next-input" id="stripe_secret" name="payment_stripe_secret" placeholder="sk_*************" value="{{ app()->environment('demo') ? '*******************************' : setting('payment_stripe_secret') }}">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="{{ STRIPE_PAYMENT_METHOD_NAME }}_payment_type">{{ __('Payment Type') }}</label>
                            <div class="ui-select-wrapper">
                                <select name="payment_{{ STRIPE_PAYMENT_METHOD_NAME }}_payment_type" class="ui-select select-search-full" id="{{ STRIPE_PAYMENT_METHOD_NAME }}_payment_type">
                                    <option value="stripe_api_charge" @if (get_payment_setting('payment_type', STRIPE_PAYMENT_METHOD_NAME, 'stripe_api_charge') == 'stripe_api_charge') selected @endif>Stripe API Charge</option>
                                    <option value="stripe_checkout" @if (get_payment_setting('payment_type', STRIPE_PAYMENT_METHOD_NAME, 'stripe_api_charge') == 'stripe_checkout') selected @endif>Stripe Checkout</option>
                                </select>
                                <svg class="svg-next-icon svg-next-icon-size-16">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                                </svg>
                            </div>
                        </div>

                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, 'stripe') !!}
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-end">
                <button class="btn btn-warning disable-payment-item @if ($stripeStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button class="btn btn-info save-payment-item btn-text-trigger-save @if ($stripeStatus == 1) hidden @endif" type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button class="btn btn-info save-payment-item btn-text-trigger-update @if ($stripeStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
