@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="max-width-1200">
        @if ($shipment->status == \Botble\Ecommerce\Enums\ShippingStatusEnum::CANCELED)
            <div class="ui-layout__item mb20">
                <div class="ui-banner ui-banner--status-warning">
                    <div class="ui-banner__ribbon">
                        <svg class="svg-next-icon svg-next-icon-size-20">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#alert-circle"></use>
                        </svg>
                    </div>
                    <div class="ui-banner__content">
                        <h2 class="ui-banner__title">{{ trans('plugins/ecommerce::shipping.shipment_canceled') }}</h2>
                        <div class="ws-nm">
                            {{ trans('plugins/ecommerce::shipping.at') }} <i>{{ BaseHelper::formatDate($shipment->updated_at, 'H:i d/m/Y') }}</i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flexbox-grid no-pd-none">
            <div class="flexbox-content">
                <div class="panel panel-default">
                    <div class="wrapper-content">
                        <div class="clearfix">
                            <div class="table-wrapper p-none">
                                <table class="order-totals-summary">
                                    <tbody>
                                    @foreach ($shipment->order->products as $orderProduct)
                                        @php
                                            $product = get_products([
                                                'condition' => [
                                                    'ec_products.id'     => $orderProduct->product_id,
                                                ],
                                                'take'   => 1,
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
                                                    'ec_products.status',
                                                    'ec_products.order',
                                                    'ec_products.created_at',
                                                ],
                                            ]);
                                        @endphp
                                        @if ($product)
                                            <tr class="border-bottom">
                                                <td class="order-border text-center p-small">
                                                    <i class="fa fa-truck"></i>
                                                </td>
                                                <td class="order-border p-small">
                                                    <div class="flexbox-grid-default pl5 p-r5" style="align-items: center">
                                                        <div class="flexbox-auto-50">
                                                            <div class="wrap-img"><img class="thumb-image thumb-image-cartorderlist" src="{{ RvMedia::getImageUrl($product->original_product->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" /></div>
                                                        </div>
                                                        <div class="flexbox-content">
                                                            <div>
                                                                <a class="wordwrap hide-print" href="{{ route('products.edit', $product->original_product->id) }}" title="{{ $orderProduct->product_name }}">{{ $orderProduct->product_name }}</a>
                                                                <p class="mb-0">
                                                                    <small>{{ $product->variation_attributes }}</small>
                                                                </p>
                                                                <p>{{ trans('plugins/ecommerce::shipping.sku') }} : <span>{{ $product->sku }}</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="order-border text-end p-small p-sm-r">
                                                    <strong class="item-quantity">{{ $orderProduct->qty }}</strong>
                                                    <span class="item-multiplier mr5">×</span><b class="color-blue-line-through">{{ format_price($orderProduct->price) }}</b>
                                                </td>
                                                <td class="order-border text-end p-small p-sm-r border-none-r">
                                                    <span>{{ format_price($orderProduct->price * $orderProduct->qty) }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>

                                </table>
                                <div class="flexbox-grid-default p-t15 p-b15 height-light bg-order">
                                    <div class="flexbox-content">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td colspan="4" class="text-center p-sm-r border-none">
                                                        <a href="{{ route('orders.edit', $shipment->order_id) }}" target="_blank" class="d-inline-block mt-2">{{ trans('plugins/ecommerce::shipping.view_order', ['order_id' => get_order_code($shipment->order_id)]) }} <i class="fa fa-external-link-alt"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('ecommerce.shipments.edit', $shipment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div id="extra-shipment-info" class="widget meta-boxes">
                        <div class="widget-title">
                            <h4><span>{{ trans('plugins/ecommerce::shipping.additional_shipment_information') }}</span></h4>
                        </div>
                        <div class="widget-body">
                            <div class="form-group mb-3">
                                <label for="shipping_company_name" class="control-label">{{ trans('plugins/ecommerce::shipping.shipping_company_name') }}</label>
                                <input type="text" name="shipping_company_name" id="shipping_company_name" value="{{ $shipment->shipping_company_name }}" class="form-control" placeholder="Ex: DHL, AliExpress...">
                            </div>
                            <div class="form-group mb-3">
                                <label for="tracking_id" class="control-label">{{ trans('plugins/ecommerce::shipping.tracking_id') }}</label>
                                <input type="text" name="tracking_id" id="tracking_id" value="{{ $shipment->tracking_id }}" class="form-control" placeholder="Ex: JJD0099999999">
                            </div>
                            <div class="form-group mb-3">
                                <label for="tracking_link" class="control-label">{{ trans('plugins/ecommerce::shipping.tracking_link') }}</label>
                                <input type="text" name="tracking_link" id="tracking_link" value="{{ $shipment->tracking_link }}" class="form-control" placeholder="Ex: https://mydhl.express.dhl/us/en/tracking.html#/track-by-reference">
                            </div>
                            <div class="form-group mb-3">
                                <label for="estimate_date_shipped" class="control-label">{{ trans('plugins/ecommerce::shipping.estimate_date_shipped') }}</label>
                                <input type="text" name="estimate_date_shipped" id="estimate_date_shipped" value="{{ $shipment->estimate_date_shipped }}" class="form-control datepicker" data-date-format="yyyy/mm/dd" placeholder="yyyy/mm/dd">
                            </div>
                            <div class="form-group mb-3">
                                <label class="control-label">{{ trans('plugins/ecommerce::shipping.note') }}</label>
                                <textarea class="form-control" name="note" rows="2" placeholder="{{ trans('plugins/ecommerce::shipping.add_note') }}">{{ $shipment->note }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="submit" value="apply" class="btn btn-success me-2">
                        <i class="fa fa-check-circle"></i> {{ trans('core/base::forms.save') }}
                    </button>

                    @if ($shipment->status != \Botble\Ecommerce\Enums\ShippingStatusEnum::CANCELED)
                        <div class="shipment-actions d-inline-block">
                            <div class="dropdown btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="mr5">{{ trans('plugins/ecommerce::shipping.update_shipping_status') }}</span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach(\Botble\Ecommerce\Enums\ShippingStatusEnum::values() as $item)
                                        <li><a data-value="{{ $item->getValue() }}" data-target="{{ route('ecommerce.shipments.update-status', $shipment->id) }}">{{ $item->label() }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @if ((float)$shipment->cod_amount)
                                <div class="dropdown btn-group p-l10">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="mr5">{{ trans('plugins/ecommerce::shipping.update_cod_status') }}</span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach(\Botble\Ecommerce\Enums\ShippingCodStatusEnum::values() as $item)
                                            <li><a data-value="{{ $item->getValue() }}" data-target="{{ route('ecommerce.shipments.update-cod-status', $shipment->id) }}">{{ $item->label() }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                </form>

                <div class="mt20 mb20 timeline-shipment">
                    <div class="comment-log ws-nm">
                        <div class="comment-log-title">
                            <label class="bold-light m-xs-b hide-print">{{ trans('plugins/ecommerce::shipping.history') }}</label>
                        </div>
                        <div class="comment-log-timeline">
                            <div class="column-left-history ps-relative" id="order-history-wrapper">
                                @foreach ($shipment->histories()->latest()->get() as $history)
                                    <div class="item-card">
                                        <div class="item-card-body clearfix">
                                            <div class="item comment-log-item comment-log-item-date ui-feed__timeline">
                                                <div class="ui-feed__item ui-feed__item--message">
                                                    <span class="ui-feed__marker @if ($history->user_id) ui-feed__marker--user-action @endif"></span>
                                                    <div class="ui-feed__message">
                                                        <div class="timeline__message-container">
                                                            <div class="timeline__inner-message">
                                                                <span>{!! OrderHelper::processHistoryVariables($history) !!}</span>
                                                            </div>
                                                            <time class="timeline__time"><span>{{ $history->created_at }}</span></time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flexbox-content flexbox-right">
                <div class="wrapper-content">
                    <div class="pd-all-20">
                        <label class="title-product-main text-no-bold">{{ trans('plugins/ecommerce::shipping.shipment_information') }}</label>
                    </div>
                    <div class="pd-all-20 p-t15 p-b15 border-top-title-main ps-relative">
                        <div class="flexbox-grid-form flexbox-grid-form-no-outside-padding mb10">
                            <div class="flexbox-grid-form-item">
                                {{ trans('plugins/ecommerce::shipping.order_number') }}
                            </div>
                            <div class="flexbox-grid-form-item text-end">
                                <a target="_blank" href="{{ route('orders.edit', $shipment->order->id) }}" class="hover-underline">{{ get_order_code($shipment->order->id) }} <i class="fa fa-external-link-alt"></i></a>
                            </div>
                        </div>
                        <div class="flexbox-grid-form flexbox-grid-form-no-outside-padding mb10">
                            <div class="flexbox-grid-form-item">
                                {{ trans('plugins/ecommerce::shipping.shipping_method') }}
                            </div>
                            <div class="flexbox-grid-form-item text-end ws-nm">
                                <label class="font-size-11px">{{ OrderHelper::getShippingMethod($shipment->order->shipping_method) }}
                                    @if ($shipment->order->shipping_option)
                                        ({{ $shipment->order->shipping_method_name }})
                                    @endif
                                </label>
                            </div>
                        </div>
                        <div class="flexbox-grid-form flexbox-grid-form-no-outside-padding mb10">
                            <div class="flexbox-grid-form-item">
                                {{ trans('plugins/ecommerce::shipping.shipping_fee') }}
                            </div>
                            <div class="flexbox-grid-form-item text-end ws-nm">
                                <label class="font-size-11px">
                                    <span>{{ format_price($shipment->price) }}</span>
                                </label>
                            </div>
                        </div>
                        @if ((float)$shipment->cod_amount)
                            <div class="flexbox-grid-form flexbox-grid-form-no-outside-padding mb10">
                                <div class="flexbox-grid-form-item">
                                    {{ trans('plugins/ecommerce::shipping.cod_amount') }}:
                                </div>
                                <div class="flexbox-grid-form-item text-end ws-nm">
                                    <label class="font-size-11px">
                                        <span>{{ format_price($shipment->cod_amount) }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flexbox-grid-form flexbox-grid-form-no-outside-padding mb10">
                                <div class="flexbox-grid-form-item">
                                    {{ trans('plugins/ecommerce::shipping.cod_status') }}
                                </div>
                                <div class="flexbox-grid-form-item text-end">
                                    {!! BaseHelper::clean($shipment->cod_status->toHtml()) !!}
                                </div>
                            </div>
                        @endif
                        <div class="flexbox-grid-form flexbox-grid-form-no-outside-padding mb10">
                            <div class="flexbox-grid-form-item">
                                {{ trans('plugins/ecommerce::shipping.shipping_status') }}
                            </div>
                            <div class="flexbox-grid-form-item text-end">
                                {!! BaseHelper::clean($shipment->status->toHtml()) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper-content mt20">
                    <div class="pd-all-20">
                        <label class="title-product-main text-no-bold">{{ trans('plugins/ecommerce::shipping.customer_information') }}</label>
                    </div>
                    <div class="pd-all-20 p-t15 p-b15 border-top-title-main ps-relative">
                        <div class="form-group ws-nm mb0">
                            <ul class="ws-nm text-infor-subdued shipping-address-info">
                                @include('plugins/ecommerce::orders.shipping-address.detail', ['address' => $shipment->order->address])
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::modalAction('confirm-change-status-modal', trans('plugins/ecommerce::shipping.change_status_confirm_title'), 'info', trans('plugins/ecommerce::shipping.change_status_confirm_description'), 'confirm-change-shipment-status-button', trans('plugins/ecommerce::shipping.accept')) !!}
@stop
