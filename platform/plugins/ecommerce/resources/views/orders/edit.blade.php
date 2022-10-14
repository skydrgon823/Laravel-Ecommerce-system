@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="max-width-1200" id="main-order-content">
        <div class="ui-layout">
            <div class="flexbox-layout-sections">
                @if ($order->status == \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                    <div class="ui-layout__section">
                        <div class="ui-layout__item">
                            <div class="ui-banner ui-banner--status-warning">
                                <div class="ui-banner__ribbon">
                                    <svg class="svg-next-icon svg-next-icon-size-20">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xlink:href="#alert-circle"></use>
                                    </svg>
                                </div>
                                <div class="ui-banner__content">
                                    <h2 class="ui-banner__title">{{ trans('plugins/ecommerce::order.order_canceled') }}</h2>
                                    <div class="ws-nm">
                                        {{ trans('plugins/ecommerce::order.order_was_canceled_at') }}
                                        <strong>{{ BaseHelper::formatDate($order->updated_at, 'H:i d/m/Y') }}</strong>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="flexbox-layout-section-primary mt20">
                    <div class="ui-layout__item">
                        <div class="wrapper-content">
                            <div class="pd-all-20">
                                <div class="flexbox-grid-default">
                                    <div class="flexbox-auto-right mr5">
                                        <label
                                            class="title-product-main text-no-bold">{{ trans('plugins/ecommerce::order.order_information') }} {{ get_order_code($order->id) }}</label>
                                    </div>
                                </div>
                                <div class="mt20">
                                    @if ($order->shipment->id)
                                        <svg
                                            class="svg-next-icon svg-next-icon-size-16 next-icon--right-spacing-quartered">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#next-orders"></use>
                                        </svg>
                                        <strong class="ml5">{{ trans('plugins/ecommerce::order.completed') }}</strong>
                                    @else
                                        <svg class="svg-next-icon svg-next-icon-size-16 svg-next-icon-gray">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#next-order-unfulfilled-16"></use>
                                        </svg>
                                        <strong class="ml5">{{ trans('plugins/ecommerce::order.completed') }}</strong>
                                    @endif
                                </div>
                            </div>
                            <div class="pd-all-20 p-none-t border-top-title-main">
                                <div class="table-wrap">
                                    <table class="table-order table-divided">
                                        <tbody>
                                        @foreach ($order->products as $orderProduct)
                                            @php
                                                $product = get_products([
                                                    'condition' => [
                                                        'ec_products.id' => $orderProduct->product_id,
                                                    ],
                                                    'take' => 1,
                                                    'select' => [
                                                        'ec_products.id',
                                                        'ec_products.images',
                                                        'ec_products.name',
                                                        'ec_products.price',
                                                        'ec_products.sale_price',
                                                        'ec_products.sale_type',
                                                        'ec_products.start_date',
                                                        'ec_products.end_date',
                                                        'ec_products.sku',
                                                        'ec_products.is_variation',
                                                        'ec_products.is_variation',
                                                        'ec_products.status',
                                                        'ec_products.order',
                                                        'ec_products.created_at',
                                                    ],
                                                ]);
                                            @endphp

                                            <tr>
                                                @if ($product)
                                                    <td class="width-60-px min-width-60-px vertical-align-t">
                                                        <div class="wrap-img"><img
                                                                class="thumb-image thumb-image-cartorderlist"
                                                                src="{{ RvMedia::getImageUrl($product->image ?: $product->original_product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                                                alt="{{ $orderProduct->product_name }}"></div>
                                                    </td>
                                                @endif
                                                <td class="pl5 p-r5 min-width-200-px">
                                                    <a class="text-underline hover-underline pre-line" target="_blank"
                                                       href="{{ $product ? route('products.edit', $product->original_product->id) : '#' }}"
                                                       title="{{ $product ? $product->original_product->name : $orderProduct->product_name }}">
                                                        {{ $product ? $product->original_product->name : $orderProduct->product_name }}
                                                    </a>
                                                    @if ($product)
                                                        &nbsp;
                                                        @if ($product->sku)
                                                            ({{ trans('plugins/ecommerce::order.sku') }}:
                                                            <strong>{{ $product->sku }}</strong>)
                                                        @endif
                                                        @if ($product->is_variation)
                                                            <p class="mb-0">
                                                                <small>{{ $product->variation_attributes }}</small>
                                                            </p>
                                                        @endif
                                                    @endif

                                                    @if (!empty($orderProduct->options) && is_array($orderProduct->options))
                                                        @foreach($orderProduct->options as $option)
                                                            @if (!empty($option['key']) && !empty($option['value']))
                                                                <p class="mb-0"><small>{{ $option['key'] }}:
                                                                        <strong> {{ $option['value'] }}</strong></small>
                                                                </p>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    {!! apply_filters(ECOMMERCE_ORDER_DETAIL_EXTRA_HTML, null) !!}
                                                    @if ($order->shipment->id)
                                                        <ul class="unstyled">
                                                            <li class="simple-note">
                                                                <a><span>{{ $orderProduct->qty }}</span><span
                                                                        class="text-lowercase"> {{ trans('plugins/ecommerce::order.completed') }}</span></a>
                                                                <ul class="dom-switch-target line-item-properties small">
                                                                    <li class="ws-nm">
                                                                        <span class="bull">↳</span>
                                                                        <span
                                                                            class="black">{{ trans('plugins/ecommerce::order.shipping') }} </span>
                                                                        <a class="text-underline bold-light"
                                                                           target="_blank"
                                                                           title="{{ $order->shipping_method_name }}"
                                                                           href="{{ route('ecommerce.shipments.edit', $order->shipment->id) }}">{{ $order->shipping_method_name }}</a>
                                                                    </li>

                                                                    @if (is_plugin_active('marketplace') && $order->store->name)
                                                                        <li class="ws-nm">
                                                                            <span class="bull">↳</span>
                                                                            <span
                                                                                class="black">{{ trans('plugins/marketplace::store.store') }}</span>
                                                                            <a href="{{ $order->store->url }}"
                                                                               class="bold-light"
                                                                               target="_blank">{{ $order->store->name }}</a>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td class="pl5 p-r5 text-end">
                                                    <div class="inline_block">
                                                        <span>{{ format_price($orderProduct->price) }}</span>
                                                    </div>
                                                </td>
                                                <td class="pl5 p-r5 text-center">x</td>
                                                <td class="pl5 p-r5">
                                                    <span>{{ $orderProduct->qty }}</span>
                                                </td>
                                                <td class="pl5 text-end">{{ format_price($orderProduct->price * $orderProduct->qty) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="pd-all-20 p-none-t">
                                <div class="flexbox-grid-default block-rps-768">
                                    <div class="flexbox-auto-right p-r5">

                                    </div>
                                    <div class="flexbox-auto-right pl5">
                                        <div class="table-wrap">
                                            <table class="table-normal table-none-border table-color-gray-text">
                                                <tbody>
                                                <tr>
                                                    <td class="text-end color-subtext">{{ trans('plugins/ecommerce::order.sub_amount') }}</td>
                                                    <td class="text-end pl10">
                                                        <span>{{ format_price($order->sub_total) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end color-subtext mt10">
                                                        <p class="mb0">{{ trans('plugins/ecommerce::order.discount') }}</p>
                                                        @if ($order->coupon_code)
                                                            <p class="mb0">{!! trans('plugins/ecommerce::order.coupon_code', ['code' => Html::tag('strong', $order->coupon_code)->toHtml()])  !!}</p>
                                                        @elseif ($order->discount_description)
                                                            <p class="mb0">{{ $order->discount_description }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="text-end p-none-b pl10">
                                                        <p class="mb0">{{ format_price($order->discount_amount) }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end color-subtext mt10">
                                                        <p class="mb0">{{ trans('plugins/ecommerce::order.shipping_fee') }}</p>
                                                        <p class="mb0 font-size-12px">{{ $order->shipping_method_name }}</p>
                                                        <p class="mb0 font-size-12px">{{ ecommerce_convert_weight($weight) }} {{ ecommerce_weight_unit(true) }}</p>
                                                    </td>
                                                    <td class="text-end p-none-t pl10">
                                                        <p class="mb0">{{ format_price($order->shipping_amount) }}</p>
                                                    </td>
                                                </tr>
                                                @if (EcommerceHelper::isTaxEnabled())
                                                    <tr>
                                                        <td class="text-end color-subtext mt10">
                                                            <p class="mb0">{{ trans('plugins/ecommerce::order.tax') }}</p>
                                                        </td>
                                                        <td class="text-end p-none-t pl10">
                                                            <p class="mb0">{{ format_price($order->tax_amount) }}</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="text-end mt10">
                                                        <p class="mb0 color-subtext">{{ trans('plugins/ecommerce::order.total_amount') }}</p>
                                                        @if ($order->payment->id)
                                                            <p class="mb0  font-size-12px"><a
                                                                    href="{{ route('payment.show', $order->payment->id) }}"
                                                                    target="_blank">{{ $order->payment->payment_channel->label() }}</a>
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td class="text-end text-no-bold p-none-t pl10">
                                                        @if ($order->payment->id)
                                                            <a href="{{ route('payment.show', $order->payment->id) }}"
                                                               target="_blank">
                                                                <span>{{ format_price($order->amount) }}</span>
                                                            </a>
                                                        @else
                                                            <span>{{ format_price($order->amount) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="border-bottom"></td>
                                                    <td class="border-bottom"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end color-subtext">{{ trans('plugins/ecommerce::order.paid_amount') }}</td>
                                                    <td class="text-end color-subtext pl10">
                                                        @if ($order->payment->id)
                                                            <a href="{{ route('payment.show', $order->payment->id) }}"
                                                               target="_blank">
                                                                <span>{{ format_price($order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->payment->amount : 0) }}</span>
                                                            </a>
                                                        @else
                                                            <span>{{ format_price($order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->payment->amount : 0) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if ($order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::REFUNDED)
                                                    <tr class="hidden">
                                                        <td class="text-end color-subtext">{{ trans('plugins/ecommerce::order.refunded_amount') }}</td>
                                                        <td class="text-end pl10">
                                                            <span>{{ format_price($order->payment->amount) }}</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr class="hidden">
                                                    <td class="text-end color-subtext">{{ trans('plugins/ecommerce::order.amount_received') }}</td>
                                                    <td class="text-end pl10">
                                                        <span>{{ format_price($order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->amount : 0) }}</span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        @if ($order->isInvoiceAvailable())
                                            <div class="text-end">
                                                <a href="{{ route('orders.generate-invoice', $order->id) }}?type=print"
                                                   class="btn btn-primary me-2" target="_blank">
                                                    <i class="fa fa-print"></i> {{ trans('plugins/ecommerce::order.print_invoice') }}
                                                </a>
                                                <a href="{{ route('orders.generate-invoice', $order->id) }}"
                                                   class="btn btn-info">
                                                    <i class="fa fa-download"></i> {{ trans('plugins/ecommerce::order.download_invoice') }}
                                                </a>
                                            </div>
                                        @endif
                                        <div class="py-3">
                                            <form action="{{ route('orders.edit', $order->id) }}">
                                                <label
                                                    class="text-title-field">{{ trans('plugins/ecommerce::order.note') }}</label>
                                                <textarea class="ui-text-area textarea-auto-height" name="description"
                                                          rows="3"
                                                          placeholder="{{ trans('plugins/ecommerce::order.add_note') }}">{{ $order->description }}</textarea>
                                                <div class="mt10">
                                                    <button type="button"
                                                            class="btn btn-primary btn-update-order">{{ trans('plugins/ecommerce::order.save') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($order->status != \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED || $order->is_confirmed)
                                <div class="pd-all-20 border-top-title-main">
                                    <div class="flexbox-grid-default flexbox-align-items-center">
                                        <div class="flexbox-auto-left">
                                            <svg
                                                class="svg-next-icon svg-next-icon-size-20 @if ($order->is_confirmed) svg-next-icon-green @else svg-next-icon-gray @endif">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-checkmark"></use>
                                            </svg>
                                        </div>
                                        <div class="flexbox-auto-right ml15 mr15 text-upper">
                                            @if ($order->is_confirmed)
                                                <span>{{ trans('plugins/ecommerce::order.order_was_confirmed') }}</span>
                                            @else
                                                <span>{{ trans('plugins/ecommerce::order.confirm_order') }}</span>
                                            @endif
                                        </div>
                                        @if (!$order->is_confirmed)
                                            <div class="flexbox-auto-left">
                                                <form action="{{ route('orders.confirm') }}">
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <button
                                                        class="btn btn-primary btn-confirm-order">{{ trans('plugins/ecommerce::order.confirm') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="pd-all-20 border-top-title-main">
                                <div class="flexbox-grid-default flexbox-flex-wrap flexbox-align-items-center">
                                    @if ($order->status == \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                        <div class="flexbox-auto-left">
                                            <svg class="svg-next-icon svg-next-icon-size-24 svg-next-icon-gray">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-error"></use>
                                            </svg>
                                        </div>
                                        <div class="flexbox-auto-content ml15 mr15 text-upper">
                                            <span>{{ trans('plugins/ecommerce::order.order_was_canceled') }}</span>
                                        </div>
                                    @elseif ($order->payment->id)
                                        <div class="flexbox-auto-left">
                                            @if (!$order->payment->status || $order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                                <svg class="svg-next-icon svg-next-icon-size-24 svg-next-icon-gray">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#next-credit-card"></use>
                                                </svg>
                                            @elseif ($order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::COMPLETED || $order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                                <svg class="svg-next-icon svg-next-icon-size-20 svg-next-icon-green">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#next-checkmark"></use>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flexbox-auto-content ml15 mr15 text-upper">
                                            @if (!$order->payment->status || $order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                                <span>{{ trans('plugins/ecommerce::order.pending_payment') }}</span>
                                            @elseif ($order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::COMPLETED)
                                                <span>{{ trans('plugins/ecommerce::order.payment_was_accepted', ['money' => format_price($order->payment->amount - $order->payment->refunded_amount)]) }}</span>
                                            @elseif ($order->payment->amount - $order->payment->refunded_amount == 0)
                                                <span>{{ trans('plugins/ecommerce::order.payment_was_refunded') }}</span>
                                            @endif
                                        </div>
                                        @if (!$order->payment->status || in_array($order->payment->status, [\Botble\Payment\Enums\PaymentStatusEnum::PENDING]))
                                            <div class="flexbox-auto-left">
                                                <button class="btn btn-primary btn-trigger-confirm-payment"
                                                        data-target="{{ route('orders.confirm-payment', $order->id) }}">{{ trans('plugins/ecommerce::order.confirm_payment') }}</button>
                                            </div>
                                        @endif
                                        @if ($order->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::COMPLETED && (($order->payment->amount - $order->payment->refunded_amount > 0) || ($order->products->sum('qty') - $order->products->sum('restock_quantity') > 0)))
                                            <div class="flexbox-auto-left">
                                                <button
                                                    class="btn btn-secondary ml10 btn-trigger-refund">{{ trans('plugins/ecommerce::order.refund') }}</button>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if (!EcommerceHelper::countDigitalProducts($order->products))
                                <div class="pd-all-20 border-top-title-main">
                                    <div class="flexbox-grid-default flexbox-flex-wrap flexbox-align-items-center">
                                        @if ($order->status == \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED && !$order->shipment->id)
                                            <div class="flexbox-auto-left">
                                                <svg class="svg-next-icon svg-next-icon-size-20 svg-next-icon-green">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#next-checkmark"></use>
                                                </svg>
                                            </div>
                                            <div class="flexbox-auto-content ml15 mr15 text-upper">
                                                <span>{{ trans('plugins/ecommerce::order.all_products_are_not_delivered') }}</span>
                                            </div>
                                        @else
                                            @if ($order->shipment->id)
                                                <div class="flexbox-auto-left">
                                                    <svg class="svg-next-icon svg-next-icon-size-20 svg-next-icon-green">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                             xlink:href="#next-checkmark"></use>
                                                    </svg>
                                                </div>
                                                <div class="flexbox-auto-content ml15 mr15 text-upper">
                                                    <span>{{ trans('plugins/ecommerce::order.delivery') }}</span>
                                                </div>
                                            @else
                                                <div class="flexbox-auto-left">
                                                    <svg class="svg-next-icon svg-next-icon-size-24 svg-next-icon-gray">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                             xlink:href="#next-shipping"></use>
                                                    </svg>
                                                </div>
                                                <div class="flexbox-auto-content ml15 mr15 text-upper">
                                                    <span>{{ trans('plugins/ecommerce::order.delivery') }}</span>
                                                </div>
                                                <div class="flexbox-auto-left">
                                                    <div class="item">
                                                        <button class="btn btn-primary btn-trigger-shipment"
                                                                data-target="{{ route('orders.get-shipment-form', $order->id) }}">{{ trans('plugins/ecommerce::order.delivery') }}</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @if (!$order->shipment->id)
                                    <div class="shipment-create-wrap hidden"></div>
                                @else
                                    @include('plugins/ecommerce::orders.shipment-detail', ['shipment' => $order->shipment])
                                @endif
                            @endif
                        </div>
                        <div class="mt20 mb20">
                            <div>
                                <div class="comment-log ws-nm">
                                    <div class="comment-log-title">
                                        <label
                                            class="bold-light m-xs-b hide-print">{{ trans('plugins/ecommerce::order.history') }}</label>
                                    </div>
                                    <div class="comment-log-timeline">
                                        <div class="column-left-history ps-relative" id="order-history-wrapper">
                                            @foreach ($order->histories()->orderBy('id', 'DESC')->get() as $history)
                                                <div class="item-card">
                                                    <div class="item-card-body clearfix">
                                                        <div
                                                            class="item comment-log-item comment-log-item-date ui-feed__timeline">
                                                            <div class="ui-feed__item ui-feed__item--message">
                                                                <span
                                                                    class="ui-feed__marker @if ($history->user_id) ui-feed__marker--user-action @endif"></span>
                                                                <div class="ui-feed__message">
                                                                    <div class="timeline__message-container">
                                                                        <div class="timeline__inner-message">
                                                                            @if (in_array($history->action, ['confirm_payment', 'refund']))
                                                                                <a href="#"
                                                                                   class="text-no-bold show-timeline-dropdown hover-underline"
                                                                                   data-target="#history-line-{{ $history->id }}">
                                                                                    <span>{{ OrderHelper::processHistoryVariables($history) }}</span>
                                                                                </a>
                                                                            @else
                                                                                <span>{{ OrderHelper::processHistoryVariables($history) }}</span>
                                                                            @endif
                                                                        </div>
                                                                        <time class="timeline__time">
                                                                            <span>{{ $history->created_at }}</span>
                                                                        </time>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if ($history->action == 'refund' && Arr::get($history->extras, 'amount', 0) > 0)
                                                                <div class="timeline-dropdown"
                                                                     id="history-line-{{ $history->id }}">
                                                                    <table>
                                                                        <tbody>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.order_number') }}</th>
                                                                            <td>
                                                                                <a href="{{ route('orders.edit', $order->id) }}"
                                                                                   title="{{ get_order_code($order->id) }}">{{ get_order_code($order->id) }}</a>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.description') }}</th>
                                                                            <td>{{ $history->description . ' ' . trans('plugins/ecommerce::order.from') . ' ' . $order->payment->payment_channel->label() }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.amount') }}</th>
                                                                            <td>{{ format_price(Arr::get($history->extras, 'amount', 0)) }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.status') }}</th>
                                                                            <td>{{ trans('plugins/ecommerce::order.successfully') }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.transaction_type') }}</th>
                                                                            <td>{{ trans('plugins/ecommerce::order.refund') }}</td>
                                                                        </tr>
                                                                        @if ($history->user->name)
                                                                            <tr>
                                                                                <th>{{ trans('plugins/ecommerce::order.staff') }}</th>
                                                                                <td>{{ $history->user->name ?: trans('plugins/ecommerce::order.n_a') }}</td>
                                                                            </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.refund_date') }}</th>
                                                                            <td>{{ $history->created_at }}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                            @if ($history->action == 'confirm_payment' && $order->payment)
                                                                <div class="timeline-dropdown"
                                                                     id="history-line-{{ $history->id }}">
                                                                    <table>
                                                                        <tbody>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.order_number') }}</th>
                                                                            <td>
                                                                                <a href="{{ route('orders.edit', $order->id) }}"
                                                                                   title="{{ get_order_code($order->id) }}">{{ get_order_code($order->id) }}</a>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.description') }}</th>
                                                                            <td>{!! trans('plugins/ecommerce::order.mark_payment_as_confirmed', ['method' => $order->payment->payment_channel->label()]) !!}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.transaction_amount') }}</th>
                                                                            <td>{{ format_price($order->payment->amount) }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.payment_gateway') }}</th>
                                                                            <td>{{ $order->payment->payment_channel->label() }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.status') }}</th>
                                                                            <td>{{ trans('plugins/ecommerce::order.successfully') }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.transaction_type') }}</th>
                                                                            <td>{{ trans('plugins/ecommerce::order.confirm') }}</td>
                                                                        </tr>
                                                                        @if ($history->user->name)
                                                                            <tr>
                                                                                <th>{{ trans('plugins/ecommerce::order.staff') }}</th>
                                                                                <td>{{ $history->user->name ?: trans('plugins/ecommerce::order.n_a') }}</td>
                                                                            </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <th>{{ trans('plugins/ecommerce::order.payment_date') }}</th>
                                                                            <td>{{ $history->created_at }}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                            @if ($history->action == 'send_order_confirmation_email')
                                                                <div class="ui-feed__item ui-feed__item--action">
                                                                    <span class="ui-feed__spacer"></span>
                                                                    <div class="timeline__action-group">
                                                                        <a href="#"
                                                                           class="btn hide-print timeline__action-button hover-underline btn-trigger-resend-order-confirmation-modal"
                                                                           data-action="{{ route('orders.send-order-confirmation-email', $history->order_id) }}">{{ trans('plugins/ecommerce::order.resend') }}</a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flexbox-layout-section-secondary mt20">
                    <div class="ui-layout__item">
                        <div class="wrapper-content mb20">
                            <div class="next-card-section p-none-b">
                                <div class="flexbox-grid-default flexbox-align-items-center">
                                    <div class="flexbox-auto-content-left">
                                        <label
                                            class="title-product-main text-no-bold">{{ trans('plugins/ecommerce::order.customer_label') }}</label>
                                    </div>
                                    <div class="flexbox-auto-left">
                                        <img class="width-30-px radius-cycle" width="40"
                                             src="{{ $order->user->id ? $order->user->avatar_url : $order->address->avatar_url }}"
                                             alt="{{ $order->address->name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="next-card-section border-none-t">
                                <div class="mb5">
                                    <strong
                                        class="text-capitalize">{{ $order->user->name ?: $order->address->name }}</strong>
                                </div>
                                @if ($order->user->id)
                                    <div>
                                        <i class="fas fa-inbox mr5"></i><span>{{ $order->user->orders()->count() }}</span> {{ trans('plugins/ecommerce::order.orders') }}
                                    </div>
                                @endif
                                <ul class="ws-nm text-infor-subdued">
                                    <li class="overflow-ellipsis"><a class="hover-underline"
                                                                     href="mailto:{{ $order->user->email ?: $order->address->email }}">{{ $order->user->email ?: $order->address->email }}</a>
                                    </li>
                                    @if ($order->user->id)
                                        <li>
                                            <div>{{ trans('plugins/ecommerce::order.have_an_account_already') }}</div>
                                        </li>
                                    @else
                                        <li>
                                            <div>{{ trans('plugins/ecommerce::order.dont_have_an_account_yet') }}</div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="next-card-section">
                                @if (!EcommerceHelper::countDigitalProducts($order->products))
                                    <div class="flexbox-grid-default flexbox-align-items-center">
                                        <div class="flexbox-auto-content-left">
                                            <label
                                                class="title-text-second"><strong>{{ trans('plugins/ecommerce::order.shipping_address') }}</strong></label>
                                        </div>
                                        @if ($order->status != \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                            <div class="flexbox-auto-content-right text-end">
                                                <a href="#" class="btn-trigger-update-shipping-address">
                                                <span data-placement="top" data-bs-toggle="tooltip"
                                                      data-bs-original-title="{{ trans('plugins/ecommerce::order.update_address') }}">
                                                    <svg class="svg-next-icon svg-next-icon-size-12">
                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                             xlink:href="#next-edit"></use>
                                                    </svg>
                                                </span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <ul class="ws-nm text-infor-subdued shipping-address-info">
                                            @include('plugins/ecommerce::orders.shipping-address.detail', ['address' => $order->shippingAddress])
                                        </ul>
                                    </div>
                                @endif

                                @if (EcommerceHelper::isBillingAddressEnabled() && $order->billingAddress->id && $order->billingAddress->id != $order->shippingAddress->id)
                                    <div class="flexbox-grid-default flexbox-align-items-center">
                                        <div class="flexbox-auto-content-left">
                                            <label
                                                class="title-text-second"><strong>{{ trans('plugins/ecommerce::order.billing_address') }}</strong></label>
                                        </div>
                                    </div>
                                    <div>
                                        <ul class="ws-nm text-infor-subdued shipping-address-info">
                                            @include('plugins/ecommerce::orders.shipping-address.detail', ['address' => $order->billingAddress])
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            @if ($order->referral()->count())
                                <div class="next-card-section">
                                    <div class="flexbox-grid-default flexbox-align-items-center mb-2">
                                        <div class="flexbox-auto-content-left">
                                            <label class="title-text-second"><strong>{{ trans('plugins/ecommerce::order.referral') }}</strong></label>
                                        </div>
                                    </div>
                                    <div>
                                        <ul class="ws-nm text-infor-subdued">
                                            @foreach (['ip',
                                                'landing_domain',
                                                'landing_page',
                                                'landing_params',
                                                'referral',
                                                'gclid',
                                                'fclid',
                                                'utm_source',
                                                'utm_campaign',
                                                'utm_medium',
                                                'utm_term',
                                                'utm_content',
                                                'referrer_url',
                                                'referrer_domain'] as $field)
                                                @if ($order->referral->{$field})
                                                    <li>{{ trans('plugins/ecommerce::order.referral_data.' . $field) }}: <strong style="word-break: break-all">{{ $order->referral->{$field} }}</strong></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if (is_plugin_active('marketplace') && $order->store->name)
                            <div class="wrapper-content bg-gray-white mb20">
                                <div class="pd-all-20">
                                    <div class="p-b10">
                                        <strong>{{ trans('plugins/marketplace::store.store') }}</strong>
                                        <ul class="p-sm-r mb-0">
                                            <li class="ws-nm">
                                                <a href="{{ $order->store->url }}" class="ww-bw text-no-bold"
                                                   target="_blank">{{ $order->store->name }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="wrapper-content bg-gray-white mb20">
                            <div class="pd-all-20">
                                <a href="{{ route('orders.reorder', ['order_id' => $order->id]) }}"
                                   class="btn btn-info">{{ trans('plugins/ecommerce::order.reorder') }}</a>&nbsp;
                                @if ($order->canBeCanceledByAdmin())
                                    <a href="#" class="btn btn-secondary btn-trigger-cancel-order"
                                       data-target="{{ route('orders.cancel', $order->id) }}">{{ trans('plugins/ecommerce::order.cancel') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($order->status != \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
            {!! Form::modalAction('resend-order-confirmation-email-modal', trans('plugins/ecommerce::order.resend_order_confirmation'), 'info', trans('plugins/ecommerce::order.resend_order_confirmation_description', ['email' => $order->user->id ? $order->user->email : $order->address->email]), 'confirm-resend-confirmation-email-button', trans('plugins/ecommerce::order.send')) !!}
            {!! Form::modalAction('cancel-shipment-modal', trans('plugins/ecommerce::order.cancel_shipping_confirmation'), 'info', trans('plugins/ecommerce::order.cancel_shipping_confirmation_description'), 'confirm-cancel-shipment-button', trans('plugins/ecommerce::order.confirm')) !!}
            {!! Form::modalAction('update-shipping-address-modal', trans('plugins/ecommerce::order.update_address'), 'info', view('plugins/ecommerce::orders.shipping-address.form', ['address' => $order->address, 'orderId' => $order->id, 'url' => route('orders.update-shipping-address', $order->address->id ?? 0)])->render(), 'confirm-update-shipping-address-button', trans('plugins/ecommerce::order.update'), 'modal-md') !!}
            {!! Form::modalAction('cancel-order-modal', trans('plugins/ecommerce::order.cancel_order_confirmation'), 'info', trans('plugins/ecommerce::order.cancel_order_confirmation_description'), 'confirm-cancel-order-button', trans('plugins/ecommerce::order.cancel_order')) !!}
            {!! Form::modalAction('confirm-payment-modal', trans('plugins/ecommerce::order.confirm_payment'), 'info', trans('plugins/ecommerce::order.confirm_payment_confirmation_description', ['method' => $order->payment->payment_channel->label()]), 'confirm-payment-order-button', trans('plugins/ecommerce::order.confirm_payment')) !!}
            {!! Form::modalAction('confirm-refund-modal', trans('plugins/ecommerce::order.refund'), 'info', view('plugins/ecommerce::orders.refund.modal', ['order' => $order, 'url' => route('orders.refund', $order->id)])->render(), 'confirm-refund-payment-button', trans('plugins/ecommerce::order.refund') . ' <span class="refund-amount-text">' . format_price($order->payment->amount - $order->payment->refunded_amount) . '</span>') !!}
            @if ($order->shipment && $order->shipment->id)
                {!! Form::modalAction('update-shipping-status-modal', trans('plugins/ecommerce::shipping.update_shipping_status'), 'info', view('plugins/ecommerce::orders.shipping-status-modal', ['shipment' => $order->shipment, 'url' => route('ecommerce.shipments.update-status', $order->shipment->id)])->render(), 'confirm-update-shipping-status-button', trans('plugins/ecommerce::order.update'), 'modal-xs') !!}
            @endif
        @endif
    </div>
@stop
