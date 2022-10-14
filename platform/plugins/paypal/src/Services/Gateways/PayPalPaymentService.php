<?php

namespace Botble\Paypal\Services\Gateways;

use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Botble\Paypal\Services\Abstracts\PayPalPaymentAbstract;
use Exception;
use Illuminate\Support\Arr;

class PayPalPaymentService extends PayPalPaymentAbstract
{
    /**
     * Make a payment
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function makePayment(array $data)
    {
        $amount = round((float)$data['amount'], $this->isSupportedDecimals() ? 2 : 0);

        $currency = $data['currency'];
        $currency = strtoupper($currency);

        $queryParams = [
            'type'          => PAYPAL_PAYMENT_METHOD_NAME,
            'amount'        => $amount,
            'currency'      => $currency,
            'order_id'      => $data['order_id'],
            'customer_id'   => Arr::get($data, 'customer_id'),
            'customer_type' => Arr::get($data, 'customer_type'),
        ];

        if ($cancelUrl = $data['return_url'] ?: PaymentHelper::getCancelURL()) {
            $this->setCancelUrl($cancelUrl);
        }

        return $this
            ->setReturnUrl($data['callback_url'] . '?' . http_build_query($queryParams))
            ->setCurrency($currency)
            ->setCustomer(Arr::get($data, 'address.email'))
            ->setItem([
                'name'     => $data['description'],
                'quantity' => 1,
                'price'    => $amount,
                'sku'      => null,
                'type'     => PAYPAL_PAYMENT_METHOD_NAME,
            ])
            ->createPayment($data['description']);
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param array $data
     * @return mixed
     */
    public function afterMakePayment(array $data)
    {
        $status = PaymentStatusEnum::COMPLETED;

        $chargeId = session('paypal_payment_id');

        $orderIds = (array)Arr::get($data, 'order_id', []);

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount'          => $data['amount'],
            'currency'        => $data['currency'],
            'charge_id'       => $chargeId,
            'order_id'        => $orderIds,
            'customer_id'     => Arr::get($data, 'customer_id'),
            'customer_type'   => Arr::get($data, 'customer_type'),
            'payment_channel' => PAYPAL_PAYMENT_METHOD_NAME,
            'status'          => $status,
        ]);

        session()->forget('paypal_payment_id');

        return $chargeId;
    }
}
