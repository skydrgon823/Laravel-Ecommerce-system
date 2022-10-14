<?php

namespace Botble\Paypal\Providers;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Paypal\Services\Gateways\PayPalPaymentService;
use Html;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerPaypalMethod'], 2, 2);

        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithPaypal'], 2, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 2);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['PAYPAL'] = PAYPAL_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 2, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == PAYPAL_PAYMENT_METHOD_NAME) {
                $value = 'Paypal';
            }

            return $value;
        }, 2, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == PAYPAL_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 2, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == PAYPAL_PAYMENT_METHOD_NAME) {
                $data = PaypalPaymentService::class;
            }

            return $data;
        }, 2, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == PAYPAL_PAYMENT_METHOD_NAME) {
                $paymentDetail = (new PayPalPaymentService())->getPaymentDetails($payment->charge_id);
                $data = view('plugins/paypal::detail', ['payment' => $paymentDetail])->render();
            }

            return $data;
        }, 2, 2);
    }

    /**
     * @param string|null $settings
     * @return string
     * @throws Throwable
     */
    public function addPaymentSettings(?string $settings): string
    {
        return $settings . view('plugins/paypal::settings')->render();
    }

    /**
     * @param string|null $html
     * @param array $data
     * @return string
     */
    public function registerPaypalMethod(?string $html, array $data): string
    {
        return $html . view('plugins/paypal::methods', $data)->render();
    }

    /**
     * @param array $data
     * @param Request $request
     * @return array
     * @throws BindingResolutionException
     */
    public function checkoutWithPaypal(array $data, Request $request): array
    {
        if ($request->input('payment_method') == PAYPAL_PAYMENT_METHOD_NAME) {
            $currentCurrency = get_application_currency();

            $currencyModel = $currentCurrency->replicate();

            $payPalService = $this->app->make(PayPalPaymentService::class);

            $supportedCurrencies = $payPalService->supportedCurrencyCodes();

            $currency = strtoupper($currentCurrency->title);

            $notSupportCurrency = false;

            if (!in_array($currency, $supportedCurrencies)) {
                $notSupportCurrency = true;

                if (!$currencyModel->where('title', 'USD')->exists()) {
                    $data['error'] = true;
                    $data['message'] = __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", [
                        'name'       => 'PayPal',
                        'currency'   => $currency,
                        'currencies' => implode(', ', $supportedCurrencies),
                    ]);

                    return $data;
                }
            }

            $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

            if ($notSupportCurrency) {
                $usdCurrency = $currencyModel->where('title', 'USD')->first();

                $paymentData['currency'] = 'USD';
                if ($currentCurrency->is_default) {
                    $paymentData['amount'] = $paymentData['amount'] * $usdCurrency->exchange_rate;
                } else {
                    $paymentData['amount'] = format_price($paymentData['amount'], $currentCurrency, true);
                }
            }

            if (!$request->input('callback_url')) {
                $paymentData['callback_url'] = route('payments.paypal.status');
            }

            $checkoutUrl = $payPalService->execute($paymentData);

            if ($checkoutUrl) {
                $data['checkoutUrl'] = $checkoutUrl;
            } else {
                $data['error'] = true;
                $data['message'] = $payPalService->getErrorMessage();
            }

            return $data;
        }

        return $data;
    }
}
