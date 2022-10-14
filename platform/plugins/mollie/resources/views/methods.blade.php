@if (get_payment_setting('status', MOLLIE_PAYMENT_METHOD_NAME) == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}"
               value="{{ MOLLIE_PAYMENT_METHOD_NAME }}" data-bs-toggle="collapse" data-bs-target=".payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_wrap"
               data-toggle="collapse" data-target=".payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_wrap"
               data-parent=".list_payment_method"
               @if ((session('selected_payment_method') ?: setting('default_payment_method')) == MOLLIE_PAYMENT_METHOD_NAME) checked @endif
        >
        <label for="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}">{{ get_payment_setting('name', MOLLIE_PAYMENT_METHOD_NAME) }}</label>
        <div class="payment_{{ MOLLIE_PAYMENT_METHOD_NAME }}_wrap payment_collapse_wrap collapse @if ((session('selected_payment_method') ?: setting('default_payment_method')) == MOLLIE_PAYMENT_METHOD_NAME) show @endif">
            <p>{!! get_payment_setting('description', MOLLIE_PAYMENT_METHOD_NAME, __('Payment with Mollie')) !!}</p>
        </div>
    </li>
@endif
