<?php

namespace Botble\Mollie\Providers;

use Botble\Mollie\Services\Gateways\MolliePaymentService;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Supports\PaymentHelper;
use Exception;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Mollie;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerMollieMethod'], 17, 2);

        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithMollie'], 17, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 99);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['MOLLIE'] = MOLLIE_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 23, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == MOLLIE_PAYMENT_METHOD_NAME) {
                $value = 'Mollie';
            }

            return $value;
        }, 23, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == MOLLIE_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 23, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == MOLLIE_PAYMENT_METHOD_NAME) {
                $data = MolliePaymentService::class;
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == MOLLIE_PAYMENT_METHOD_NAME) {
                try {
                    $paymentService = (new MolliePaymentService());
                    $paymentDetail = $paymentService->getPaymentDetails($payment->charge_id);
                    if ($paymentDetail) {
                        $data = view('plugins/mollie::detail', ['payment' => $paymentDetail])->render();
                    }
                } catch (Exception $exception) {
                    return $data;
                }
            }

            return $data;
        }, 20, 2);
    }

    /**
     * @param string $settings
     * @return string
     * @throws Throwable
     */
    public function addPaymentSettings($settings)
    {
        return $settings . view('plugins/mollie::settings')->render();
    }

    /**
     * @param string $html
     * @param array $data
     * @return string
     */
    public function registerMollieMethod($html, array $data)
    {
        return $html . view('plugins/mollie::methods', $data)->render();
    }

    /**
     * @param Request $request
     * @param array $data
     */
    public function checkoutWithMollie(array $data, Request $request)
    {
        if ($request->input('payment_method') == MOLLIE_PAYMENT_METHOD_NAME) {
            $orderIds = (array)$request->input('order_id', []);

            $orderCodes = collect($orderIds)->map(function ($item) {
                return get_order_code($item);
            });

            try {
                $response = Mollie::api()->payments->create([
                    'amount'      => [
                        'currency' => $request->input('currency'),
                        'value'    => number_format((float)$request->input('amount'), 2, '.', ''),
                    ],
                    'description' => 'Order(s) ' . $orderCodes->implode(', '),
                    'redirectUrl' => PaymentHelper::getRedirectURL(),
                    'webhookUrl'  => route('mollie.payment.callback'),
                    'metadata'    => ['order_id' => $orderIds],
                ]);

                header('Location: ' . $response->getCheckoutUrl());
                exit;
            } catch (Exception $exception) {
                $data['error'] = true;
                $data['message'] = $exception->getMessage();
            }
        }

        return $data;
    }
}
