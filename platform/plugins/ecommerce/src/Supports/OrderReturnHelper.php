<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Events\OrderReturnedEvent;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderReturn;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Carbon\Carbon;
use DB;
use EmailHandler;
use Illuminate\Support\Facades\Log;
use OrderHelper as OrderHelperFacade;
use Throwable;

class OrderReturnHelper
{
    /**
     * @param Order $order
     * @param array $data
     * @return array
     * @throws Throwable
     */
    public function returnOrder(Order $order, array $data): array
    {
        $orderReturnData = [
            'order_id'      => $order->id,
            'store_id'      => $order->store_id,
            'user_id'       => $order->user_id,
            'reason'        => $data['reason'],
            'order_status'  => $order->status,
            'return_status' => OrderReturnStatusEnum::PENDING,
        ];

        DB::beginTransaction();

        try {
            $orderReturn = app(OrderReturnInterface::class)->create($orderReturnData);

            $orderReturnItemData = [];

            $orderProductIds = [];

            foreach ($data['items'] as $returnItem) {
                $orderProduct = app(OrderProductInterface::class)->findById($returnItem['order_item_id']);
                if (!$orderProduct) {
                    continue;
                }

                if (!EcommerceHelper::canCustomReturnProductQty()) {
                    $returnItem['qty'] = $orderProduct->qty;
                }

                $orderReturnItemData[] = [
                    'order_return_id'  => $orderReturn->id,
                    'order_product_id' => $returnItem['order_item_id'],
                    'product_id'       => $orderProduct->product_id,
                    'product_name'     => $orderProduct->product_name,
                    'price'            => $orderProduct->price,
                    'qty'              => $returnItem['qty'],
                    'reason'           => $returnItem['reason'] ?? null,
                    'created_at'       => Carbon::now(),
                ];

                $orderProductIds[] = $orderProduct->product_id;
            }

            app(OrderReturnItemInterface::class)->insert($orderReturnItemData);

            event(new OrderReturnedEvent($orderReturn));

            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
            if ($mailer->templateEnabled('order-return-request')) {
                $mailer = OrderHelperFacade::setEmailVariables($order);

                $orderProducts = app(OrderProductInterface::class)->advancedGet([
                    'condition' => [
                        'order_id' => $order->id,
                        ['product_id', 'IN', $orderProductIds],
                    ],
                ]);

                $order->dont_show_order_info_in_product_list = true;

                $mailer->setVariableValues([
                    'list_order_products' => view('plugins/ecommerce::emails.partials.order-detail', [
                        'order'    => $order,
                        'products' => $orderProducts,
                    ])
                        ->render(),
                    'return_reason' => $orderReturn->reason->label(),
                ]);

                $mailer->sendUsingTemplate('order-return-request', get_admin_email()->toArray());
            }

            DB::commit();

            return [true, $orderReturn, null];
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [
                'file'     => $exception->getFile(),
                'function' => __FUNCTION__,
                'line'     => $exception->getLine(),
                'trace'    => $exception->getTraceAsString(),
            ]);

            return [false, [], $exception->getMessage()];
        }
    }

    /**
     * @param OrderReturn $orderReturn
     * @return array
     */
    public function cancelReturnOrder(OrderReturn $orderReturn): array
    {
        $orderReturn->return_status = OrderReturnStatusEnum::CANCELED;
        $orderReturn->save();

        return [true, $orderReturn];
    }

    /**
     * @param OrderReturn $orderReturn
     * @param array $data
     * @return array
     * @throws Throwable
     */
    public function updateReturnOrder(OrderReturn $orderReturn, array $data): array
    {
        DB::beginTransaction();
        try {
            $orderReturn->return_status = $data['return_status'];
            $orderReturn->save();

            if ($orderReturn->return_status == OrderReturnStatusEnum::COMPLETED) {
                $orderReturnItems = $orderReturn->load('items')->items;
                foreach ($orderReturnItems as $item) {
                    $product = app(ProductInterface::class)->findById($item->product_id);
                    $product->quantity += $item->qty;
                    $product->save();

                    if ($product->is_variation) {
                        $originalProduct = $product->original_product;
                        if ($originalProduct->id != $product->id) {
                            $originalProduct->quantity += $item->qty;
                            $originalProduct->save();
                        }
                    }
                }
            }
            DB::commit();
            return [true, $orderReturn];
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [
                'file'     => $exception->getFile(),
                'function' => __FUNCTION__,
                'line'     => $exception->getLine(),
                'trace'    => $exception->getTraceAsString(),
            ]);

            return [false, []];
        }
    }
}
