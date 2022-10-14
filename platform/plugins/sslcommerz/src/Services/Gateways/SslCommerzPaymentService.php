<?php

namespace Botble\SslCommerz\Services\Gateways;

use Botble\SslCommerz\Services\Abstracts\SslCommerzPaymentAbstract;
use Exception;
use Illuminate\Http\Request;

class SslCommerzPaymentService extends SslCommerzPaymentAbstract
{
    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     * @throws Exception
     */
    public function makePayment(Request $request)
    {
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function afterMakePayment(Request $request)
    {
    }
}
