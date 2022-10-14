<?php

namespace Botble\Stripe\Services\Gateways;

use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Botble\Stripe\Services\Abstracts\StripePaymentAbstract;
use Botble\Stripe\Supports\StripeHelper;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Exception\ApiErrorException;

class StripePaymentService extends StripePaymentAbstract
{
    /**
     * Make a payment
     *
     * @param array $data
     * @return mixed
     * @throws ApiErrorException
     */
    public function makePayment(array $data)
    {
        $request = request();
        $this->amount = $data['amount'];
        $this->currency = strtoupper($data['currency']);

        $this->setClient();

        if ($this->isStripeApiCharge()) {
            if (!$this->token) {
                $this->setErrorMessage(trans('plugins/payment::payment.could_not_get_stripe_token'));

                Log::error(
                    trans('plugins/payment::payment.could_not_get_stripe_token'),
                    PaymentHelper::formatLog(
                        [
                            'error'         => 'missing Stripe token',
                            'last_4_digits' => $request->input('last4Digits'),
                            'name'          => $request->input('name'),
                            'client_IP'     => $request->input('clientIP'),
                            'time_created'  => $request->input('timeCreated'),
                            'live_mode'     => $request->input('liveMode'),
                        ],
                        __LINE__,
                        __FUNCTION__,
                        __CLASS__
                    )
                );

                return false;
            }

            $charge = Charge::create([
                'amount'      => $this->convertAmount($this->amount),
                'currency'    => $this->currency,
                'source'      => $this->token,
                'description' => trans('plugins/payment::payment.payment_description', [
                    'order_id' => Arr::first($data['order_id']),
                    'site_url' => $request->getHost(),
                ]),
                'metadata'    => ['order_id' => json_encode($data['order_id'])],
            ]);

            $this->chargeId = $charge['id'];

            if ($this->chargeId) {
                // Hook after made payment
                $this->afterMakePayment($this->chargeId, $data);
            }

            return $this->chargeId;
        }

        $lineItems = [];

        foreach ($data['products'] as $product) {
            $lineItems[] = [
                'price_data' => [
                    'product_data' => [
                        'name'        => $product['name'],
                        'metadata'    => [
                            'pro_id' => $product['id'],
                        ],
                        'description' => $product['name'],
                    ],
                    'unit_amount'  => $this->convertAmount($product['price_per_order'] * get_current_exchange_rate()),
                    'currency'     => $this->currency,
                ],
                'quantity'   => $product['qty'],
            ];
        }

        $requestData = [
            'line_items'  => $lineItems,
            'mode'        => 'payment',
            'success_url' => route('payments.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('payments.stripe.error'),
            'metadata'    => [
                'order_id'      => json_encode($data['order_id']),
                'amount'        => $this->amount,
                'currency'      => $this->currency,
                'customer_id'   => Arr::get($data, 'customer_id'),
                'customer_type' => Arr::get($data, 'customer_type'),
                'return_url'    => Arr::get($data, 'return_url'),
                'callback_url'  => Arr::get($data, 'callback_url'),
            ],
        ];

        if (!empty($data['shipping_method'])) {
            $requestData['shipping_options'] = [
                [
                    'shipping_rate_data' => [
                        'type'         => 'fixed_amount',
                        'fixed_amount' => [
                            'amount'   => $this->convertAmount($data['shipping_amount'] * get_current_exchange_rate()),
                            'currency' => $this->currency,
                        ],
                        'display_name' => $data['shipping_method'],
                    ],
                ],
            ];
        }

        $checkoutSession = StripeCheckoutSession::create($requestData);

        return $checkoutSession->url;
    }

    /**
     * @param $amount
     * @return int
     */
    protected function convertAmount($amount): int
    {
        $multiplier = StripeHelper::getStripeCurrencyMultiplier($this->currency);

        if ($multiplier > 1) {
            $amount = (int)(round((float)$amount, 2) * $multiplier);
        } else {
            $amount = (int)$amount;
        }

        return $amount;
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param string $chargeId
     * @param array $data
     * @return string
     */
    public function afterMakePayment($chargeId, array $data)
    {
        try {
            $payment = $this->getPaymentDetails($chargeId);
            if ($payment && ($payment->paid || $payment->status == 'succeeded')) {
                $paymentStatus = PaymentStatusEnum::COMPLETED;
            } else {
                $paymentStatus = PaymentStatusEnum::FAILED;
            }
        } catch (Exception $exception) {
            $paymentStatus = PaymentStatusEnum::FAILED;
        }

        $orderIds = (array)$data['order_id'];

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount'          => $data['amount'],
            'currency'        => $data['currency'],
            'charge_id'       => $chargeId,
            'order_id'        => $orderIds,
            'customer_id'     => Arr::get($data, 'customer_id'),
            'customer_type'   => Arr::get($data, 'customer_type'),
            'payment_channel' => STRIPE_PAYMENT_METHOD_NAME,
            'status'          => $paymentStatus,
        ]);

        return $chargeId;
    }

    /**
     * @return bool
     */
    public function isStripeApiCharge(): bool
    {
        $key = 'stripe_api_charge';

        return get_payment_setting('payment_type', STRIPE_PAYMENT_METHOD_NAME, $key) == $key;
    }
}
