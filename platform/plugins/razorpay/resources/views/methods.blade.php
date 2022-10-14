@if (get_payment_setting('status', RAZORPAY_PAYMENT_METHOD_NAME) == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}"
               value="{{ RAZORPAY_PAYMENT_METHOD_NAME }}" data-bs-toggle="collapse" data-bs-target=".payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}_wrap"
               data-toggle="collapse" data-target=".payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}_wrap"
               data-parent=".list_payment_method"
               @if ((session('selected_payment_method') ?: setting('default_payment_method')) == RAZORPAY_PAYMENT_METHOD_NAME) checked @endif
        >
        <label for="payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}">{{ get_payment_setting('name', RAZORPAY_PAYMENT_METHOD_NAME) }}</label>
        <div class="payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}_wrap payment_collapse_wrap collapse @if ((session('selected_payment_method') ?: setting('default_payment_method')) == RAZORPAY_PAYMENT_METHOD_NAME) show @endif">
            @if ($errorMessage)
                <div class="text-danger my-2">
                    {!! BaseHelper::clean($errorMessage) !!}
                </div>
            @else
                <p>{!! get_payment_setting('description', RAZORPAY_PAYMENT_METHOD_NAME, __('Payment with Razorpay')) !!}</p>
            @endif

            @php $supportedCurrencies = (new \Botble\Razorpay\Services\Gateways\RazorpayPaymentService)->supportedCurrencyCodes(); @endphp
            @if (!in_array(get_application_currency()->title, $supportedCurrencies))
                <div class="alert alert-warning" style="margin-top: 15px;">
                    {{ __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", ['name' => 'Razorpay', 'currency' => get_application_currency()->title, 'currencies' => implode(', ', $supportedCurrencies)]) }}

                    <div style="margin-top: 10px;">
                        {{ __('Learn more') }}: <a href="https://razorpay.com/docs/payments/payments/international-payments/#supported-currencies" target="_blank" rel="nofollow">https://razorpay.com/docs/payments/payments/international-payments/#supported-currencies</a>
                    </div>

                    @php
                        $currencies = get_all_currencies();

                        $currencies = $currencies->filter(function ($item) use ($supportedCurrencies) { return in_array($item->title, $supportedCurrencies); });
                    @endphp
                    @if (count($currencies))
                        <div style="margin-top: 10px;">{{ __('Please switch currency to any supported currency') }}:&nbsp;&nbsp;
                            @foreach ($currencies as $currency)
                                <a href="{{ route('public.change-currency', $currency->title) }}" @if (get_application_currency_id() == $currency->id) class="active" @endif><span>{{ $currency->title }}</span></a>
                                @if (!$loop->last)
                                    &nbsp; | &nbsp;
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
        <input type="hidden" id="rzp_order_id" value="{{ $orderId }}">
    </li>

    <script>
        $(document).ready(function () {

            var validatedFormFields = () => {
                var addressId = $('#address_id').val();
                if (addressId && addressId !== 'new') {
                    return true;
                }

                var validated = true;
                $.each($(document).find('.address-control-item-required'), (index, el) => {
                    if (!$(el).val()) {
                        validated = false;
                    }
                });

                return validated;
            }

            $('.payment-checkout-form').on('submit', function (e) {
                if (validatedFormFields() && $('input[name=payment_method]:checked').val() === 'razorpay' && !$('input[name=razorpay_payment_id]').val()) {
                    e.preventDefault();
                }
            });

            var loadExternalScript = function(path) {
                var result = $.Deferred(),
                    script = document.createElement('script');

                script.async = 'async';
                script.type = 'text/javascript';
                script.src = path;
                script.onload = script.onreadystatechange = function(_, isAbort) {
                    if (!script.readyState || /loaded|complete/.test(script.readyState)) {
                        if (isAbort) {
                            result.reject();
                        }
                        else {
                            result.resolve();
                        }
                    }
                };

                script.onerror = function() {
                    result.reject();
                };

                $('head')[0].appendChild(script);

                return result.promise();
            }

            var callRazorPayScript = function() {
                loadExternalScript('https://checkout.razorpay.com/v1/checkout.js').then(function() {

                    var options = {
                        key: '{{ get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME) }}',
                        name: '{{ $name }}',
                        description: '{{ $name }}',
                        order_id: $('#rzp_order_id').val(),
                        handler: function (transaction) {
                            var form = $('.payment-checkout-form');
                            form.append($('<input type="hidden" name="razorpay_payment_id">').val(transaction.razorpay_payment_id));
                            form.append($('<input type="hidden" name="razorpay_order_id">').val(transaction.razorpay_order_id));
                            form.append($('<input type="hidden" name="razorpay_signature">').val(transaction.razorpay_signature));
                            form.submit();
                        },
                        'prefill': {
                            'name': $('#address_name').val(),
                            'email': $('#address_email').val(),
                            'contact': $('#address_phone').val()
                        },
                    };

                    window.rzpay = new Razorpay(options);
                    rzpay.open();
                });
            }

            $(document).off('click', '.payment-checkout-btn').on('click', '.payment-checkout-btn', function (event) {
                event.preventDefault();

                var _self = $(this);
                var form = _self.closest('form');
                if (validatedFormFields()) {
                    _self.attr('disabled', 'disabled');
                    var submitInitialText = _self.html();
                    _self.html('<i class="fa fa-gear fa-spin"></i> ' + _self.data('processing-text'));

                    var method = $('input[name=payment_method]:checked').val();

                    if (method === 'stripe' && $('.stripe-card-wrapper').length > 0) {
                        Stripe.setPublishableKey($('#payment-stripe-key').data('value'));
                        Stripe.card.createToken(form, function (status, response) {
                            if (response.error) {
                                if (typeof Botble != 'undefined') {
                                    Botble.showError(response.error.message, _self.data('error-header'));
                                } else {
                                    alert(response.error.message);
                                }
                                _self.removeAttr('disabled');
                                _self.html(submitInitialText);
                            } else {
                                form.append($('<input type="hidden" name="stripeToken">').val(response.id));
                                form.submit();
                            }
                        });
                    } else if (method === 'razorpay') {

                        callRazorPayScript();

                        _self.removeAttr('disabled');
                        _self.html(submitInitialText);
                    } else {
                        form.submit();
                    }
                } else {
                    form.submit();
                }
            });
        });
    </script>
@endif
