<?php

namespace Botble\Paystack\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Illuminate\Http\Request;
use Paystack;
use Throwable;

class PaystackController extends BaseController
{
    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getPaymentStatus(Request $request, BaseHttpResponse $response)
    {
        $result = Paystack::getPaymentData();

        if (!$result['status']) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage($result['message']);
        }

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount'          => $request->input('amount'),
            'currency'        => $result['data']['currency'],
            'charge_id'       => $request->input('reference'),
            'payment_channel' => PAYSTACK_PAYMENT_METHOD_NAME,
            'status'          => PaymentStatusEnum::COMPLETED,
            'customer_id'     => $request->input('customer_id'),
            'customer_type'   => $request->input('customer_type'),
            'payment_type'    => 'direct',
            'order_id'        => (array)$result['data']['metadata']['order_id'],
        ], $request);

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL())
            ->setMessage(__('Checkout successfully!'));
    }
}
