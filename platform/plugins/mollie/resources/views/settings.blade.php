@php $mollieStatus = get_payment_setting('status', MOLLIE_PAYMENT_METHOD_NAME); @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/mollie/images/mollie.png') }}"
                 alt="Mollie">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://mollie.com" target="_blank">{{ __('Mollie') }}</a>
                    <p>{{ __('Customer can buy product and pay directly using Visa, Credit card via :name', ['name' => 'Mollie']) }}</p>
                </li>
            </ul>
        </td>
    </tr>
    <tr class="bg-white">
        <td colspan="3">
            <div class="float-start" style="margin-top: 5px;">
                <div
                    class="payment-name-label-group @if (get_payment_setting('status', MOLLIE_PAYMENT_METHOD_NAME) == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ get_payment_setting('name', MOLLIE_PAYMENT_METHOD_NAME) }}</label>
                </div>
            </div>
            <div class="float-end">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($mollieStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($mollieStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', MOLLIE_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'Mollie']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'Mollie']) }}
                                :</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://www.mollie.com/dashboard/signup" target="_blank">
                                        {{ __('Register an account on :name', ['name' => 'Mollie']) }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('After registration at :name, you will have API key', ['name' => 'Mollie']) }}</p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('Enter API key into the box in right hand') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="mollie_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input" name="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_name"
                                   id="mollie_name" data-counter="400"
                                   value="{{ get_payment_setting('name', MOLLIE_PAYMENT_METHOD_NAME, __('Online payment via :name', ['name' => 'Mollie'])) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_description">{{ trans('core/base::forms.description') }}</label>
                            <textarea class="next-input" name="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_description" id="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_description">{{ get_payment_setting('description', MOLLIE_PAYMENT_METHOD_NAME, __('Payment with Mollie')) }}</textarea>
                        </div>
                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank" href="https://mollie.com">Mollie</a>:
                        </p>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="{{ MOLLIE_PAYMENT_METHOD_NAME }}_api_key">{{ __('API Key') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_api_key" id="{{ MOLLIE_PAYMENT_METHOD_NAME }}_api_key"
                                   value="{{ get_payment_setting('api_key', MOLLIE_PAYMENT_METHOD_NAME) }}">
                        </div>

                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, MOLLIE_PAYMENT_METHOD_NAME) !!}
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-end">
                <button class="btn btn-warning disable-payment-item @if ($mollieStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($mollieStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($mollieStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
