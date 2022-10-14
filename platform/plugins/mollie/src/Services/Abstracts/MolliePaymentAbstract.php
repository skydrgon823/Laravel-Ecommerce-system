<?php

namespace Botble\Mollie\Services\Abstracts;

use Botble\Payment\Services\Traits\PaymentErrorTrait;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mollie;

abstract class MolliePaymentAbstract implements ProduceServiceInterface
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
     * MolliePaymentAbstract constructor.
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
        $this->client = Mollie::api();

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
            $response  = $this->client->payments->get($paymentId); // Returns a particular payment
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return false;
        }

        return $response;
    }

    /**
     * This function can be used to preform refund on the capture.
     */
    public function refundOrder($paymentId, $amount, array $options = [])
    {
        try {
            $payment = $this->client->payments->get($paymentId);

            if ($payment->canBeRefunded() &&
                $payment->amountRemaining->currency == $this->paymentCurrency &&
                (float) $payment->amountRemaining->value >= (float) $amount) {
                /*
                 * https://docs.mollie.com/reference/v2/refunds-api/create-refund
                 */
                $description = Arr::get($options, 'refund_note') ?: get_order_code(Arr::get($options, 'order_id'));
                $refund = $payment->refund([
                    'amount' => [
                        'currency' => $this->paymentCurrency,
                        'value'    => number_format((float) $amount, 2, '.', ''), // You must send the correct number of decimals, thus we enforce the use of strings
                    ],
                    'description' => Str::limit($description, 140),
                    'metadata'    => $options,
                ]);

                return [
                    'error'   => false,
                    'message' => "{$refund->amount->currency} {$refund->amount->value} of payment {$paymentId} refunded.",
                    'data'    => (array) $refund,
                ];
            }

            return [
                'error'   => true,
                'message' => "Payment {$paymentId} can not be refunded.",
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
