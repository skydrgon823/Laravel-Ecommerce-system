<?php

namespace Botble\SslCommerz\Services\Abstracts;

use Botble\Payment\Services\Traits\PaymentErrorTrait;
use Botble\SslCommerz\Services\SslCommerz;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

abstract class SslCommerzPaymentAbstract implements ProduceServiceInterface
{
    use PaymentErrorTrait;

    /**
     * @var string
     */
    protected $paymentCurrency;

    /**
     * @var object
     */
    protected $client;

    /**
     * @var bool
     */
    protected $supportRefundOnline;

    /**
     * SslCommerzPaymentAbstract constructor.
     */
    public function __construct()
    {
        $this->paymentCurrency = config('plugins.payment.payment.currency');

        $this->totalAmount = 0;

        $this->setClient();

        $this->supportRefundOnline = true;
    }

    /**
     * @return bool
     */
    public function getSupportRefundOnline()
    {
        return $this->supportRefundOnline;
    }

    /**
     * Set client
     * @return self
     */
    public function setClient()
    {
        $this->client = new SslCommerz();

        return $this;
    }

    /**
     * @return object
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set payment currency
     *
     * @param string $currency String name of currency
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->paymentCurrency = $currency;

        return $this;
    }

    /**
     * Get current payment currency
     *
     * @return string Current payment currency
     */
    public function getCurrency()
    {
        return $this->paymentCurrency;
    }

    /**
     * Get payment details
     *
     * @param string $paymentId
     * @return mixed Object payment details
     * @throws Exception
     */
    public function getPaymentDetails($paymentId)
    {
        try {
            $payment = $this->client->getPaymentDetails($paymentId);
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return false;
        }

        return $payment;
    }

    /**
     * This function can be used to preform refund on the capture.
     */
    public function refundOrder($paymentId, $amount, array $options = [])
    {
        try {
            $detail = $this->client->getPaymentDetails($paymentId);
            $bankTranId = Arr::get($detail, 'element.0.bank_tran_id');
            if ($bankTranId) {
                $response = $this->client->refundOrder($bankTranId, $amount, $options);
                $status = Arr::get($response, 'status');
                if ($status == 'success') {
                    $response = array_merge($response, ['_refund_id' => Arr::get($response, 'refund_ref_id')]);
                    return [
                        'error'   => false,
                        'message' => $status,
                        'data'    => (array) $response,
                    ];
                }
                return [
                    'error'   => true,
                    'message' => trans('plugins/payment::payment.status_is_not_completed'),
                ];
            }

            return [
                'error'   => true,
                'message' => 'Payment ' . $paymentId . ' can not found bank_tran_id',
            ];
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * @param string $refundRefId
     */
    public function refundDetail($refundRefId)
    {
        try {
            $response = (array) $this->client->refundDetail($refundRefId);
            $status = Arr::get($response, 'status');
            return [
                'error'   => false,
                'message' => $status,
                'data'    => (array) $response,
                'status'  => $status,
            ];
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * Execute main service
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function execute(Request $request)
    {
        try {
            return $this->makePayment($request);
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return false;
        }
    }

    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    abstract public function makePayment(Request $request);

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    abstract public function afterMakePayment(Request $request);
}
