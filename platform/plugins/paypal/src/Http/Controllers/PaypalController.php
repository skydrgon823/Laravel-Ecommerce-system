<?php

namespace Botble\Paypal\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Paypal\Http\Requests\PayPalPaymentCallbackRequest;
use Botble\Paypal\Services\Gateways\PayPalPaymentService;
use Botble\Payment\Supports\PaymentHelper;
use Illuminate\Routing\Controller;

class PaypalController extends Controller
{
    /**
     * @param PayPalPaymentCallbackRequest $request
     * @param PayPalPaymentService $payPalPaymentService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getCallback(
        PayPalPaymentCallbackRequest $request,
        PayPalPaymentService         $payPalPaymentService,
        BaseHttpResponse             $response
    ) {
        $status = $payPalPaymentService->getPaymentStatus($request);

        if (!$status) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage(__('Payment failed!'));
        }

        $payPalPaymentService->afterMakePayment($request->input());

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL())
            ->setMessage(__('Checkout successfully!'));
    }
}
