<?php

namespace Botble\Stripe\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Botble\Stripe\Http\Requests\StripePaymentCallbackRequest;
use Botble\Stripe\Services\Gateways\StripePaymentService;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    /**
     * @param StripePaymentCallbackRequest $request
     * @param StripePaymentService $stripePaymentService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function success(StripePaymentCallbackRequest $request, StripePaymentService $stripePaymentService, BaseHttpResponse $response)
    {
        try {
            $stripePaymentService->setClient();

            $session = Session::retrieve($request->input('session_id'));

            if ($session->payment_status == 'paid') {
                $metadata = $session->metadata->toArray();

                $orderIds = json_decode($metadata['order_id'], true);

                $charge = PaymentIntent::retrieve($session->payment_intent);

                $chargeId = $charge->charges->first()->id;

                do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                    'amount'          => $metadata['amount'],
                    'currency'        => strtoupper($session->currency),
                    'charge_id'       => $chargeId,
                    'order_id'        => $orderIds,
                    'customer_id'     => Arr::get($metadata, 'customer_id'),
                    'customer_type'   => Arr::get($metadata, 'customer_type'),
                    'payment_channel' => STRIPE_PAYMENT_METHOD_NAME,
                    'status'          => PaymentStatusEnum::COMPLETED,
                ]);

                return $response
                    ->setNextUrl(PaymentHelper::getRedirectURL() . '?charge_id=' . $chargeId)
                    ->setMessage(__('Checkout successfully!'));
            }

            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage(__('Payment failed!'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage($exception->getMessage() ?: __('Payment failed!'));
        }
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function error(BaseHttpResponse $response)
    {
        return $response
            ->setError()
            ->setNextUrl(PaymentHelper::getCancelURL())
            ->withInput()
            ->setMessage(__('Payment failed!'));
    }
}
