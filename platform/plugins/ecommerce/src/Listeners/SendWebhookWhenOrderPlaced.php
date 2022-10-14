<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderPlacedEvent;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\URL;
use Throwable;

class SendWebhookWhenOrderPlaced
{
    /**
     * Handle the event.
     *
     * @param OrderPlacedEvent $event
     * @return false
     * @throws Throwable
     */
    public function handle(OrderPlacedEvent $event)
    {
        $webhookURL = get_ecommerce_setting('order_placed_webhook_url');

        if (!$webhookURL || !URL::isValidUrl($webhookURL) || app()->environment('demo')) {
            return false;
        }

        try {
            $order = $event->order;

            $data = [
                'id'                   => $order->id,
                'status'               => [
                    'value' => $order->status,
                    'text'  => $order->status->label(),
                ],
                'shipping_status'      => $order->shipment->id ? [
                    'value' => $order->shipment->status,
                    'text'  => $order->shipment->status->label(),
                ] : [],
                'payment_method'       => $order->payment->id ? [
                    'value' => $order->payment->payment_channel,
                    'text'  => $order->payment->payment_channel->label(),
                ] : [],
                'payment_status'       => $order->payment->id ? [
                    'value' => $order->payment->status,
                    'text'  => $order->payment->status->label(),
                ] : [],
                'customer'             => [
                    'id'   => $order->user->id,
                    'name' => $order->user->name,
                ],
                'sub_total'            => $order->sub_total,
                'tax_amount'           => $order->tax_amount,
                'shipping_method'      => $order->shipping_method,
                'shipping_option'      => $order->shipping_option,
                'shipping_amount'      => $order->shipping_amount,
                'amount'               => $order->amount,
                'coupon_code'          => $order->coupon_code,
                'discount_amount'      => $order->discount_amount,
                'discount_description' => $order->discount_description,
                'note'                 => $order->description,
                'is_confirmed'         => $order->is_confirmed,
            ];

            $client = new Client(['verify' => false]);

            $result = $client->post($webhookURL, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
                'json'    => $data,
            ]);

            return $result->getStatusCode() == 200;
        } catch (Exception|GuzzleException $exception) {
            return false;
        }
    }
}
