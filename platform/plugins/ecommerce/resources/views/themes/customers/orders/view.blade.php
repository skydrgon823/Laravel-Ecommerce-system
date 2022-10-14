@extends(EcommerceHelper::viewPath('customers.master'))
@section('content')
    <h2 class="customer-page-title">{{ __('Order information') }}</h2>
    <div class="clearfix"></div>
    <br>

    <div class="customer-order-detail">

        <div class="row">
            <div class="col-md-6">
                <div class="order-slogan">
                    <img width="100" src="{{ RvMedia::getImageUrl(theme_option('logo')) }}"
                         alt="{{ theme_option('site_title') }}">
                    <br/>
                    {{ setting('contact_address') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="order-meta">
                    <p><span>{{ __('Order number') }}:</span> <span
                            class="order-detail-value">{{ get_order_code($order->id) }}</span></p>
                    <span>{{ __('Time') }}:</span> <span
                        class="order-detail-value">{{ $order->created_at->format('h:m d/m/Y') }}</span>
                </div>
            </div>

        </div>

        <div class="row">

            <h5>{{ __('Order information') }}</h5>
            <div class="col-md-12">
                <span>{{ __('Order status') }}:</span> <span
                    class="order-detail-value">{{ $order->status->label() }}</span>
            </div>

            <div class="col-md-12">
                <span>{{ __('Payment method') }}:</span> <span
                    class="order-detail-value"> {{ $order->payment->payment_channel->label() }} </span>
                <br>
                <span>{{ __('Payment status') }}:</span> <span
                    class="order-detail-value">{{ $order->payment->status->label() }}</span>
            </div>

            <div class="col-md-12">
                <span>{{ __('Amount') }}:</span> <span
                    class="order-detail-value"> {{ format_price($order->amount) }} </span>
            </div>

            @if (EcommerceHelper::isTaxEnabled())
                <div class="col-md-12">
                    <span>{{ __('Tax') }}:</span> <span
                        class="order-detail-value"> {{ format_price($order->tax_amount) }} </span>
                </div>
            @endif

            <div class="col-md-12">
                <span>{{ __('Shipping fee') }}:</span> <span
                    class="order-detail-value">  {{ format_price($order->shipping_amount) }} </span>
            </div>

            <div class="col-md-12">
                @if ($order->description)
                    <span>{{ __('Note') }}:</span> <span class="order-detail-value text-warning">{{ $order->description }} </span>&nbsp;
                @endif
            </div>

            <h5>{{ __('Customer information') }}</h5>

            <div class="col-md-12">
                <span>{{ __('Full Name') }}:</span> <span class="order-detail-value">{{ $order->address->name }} </span>
            </div>

            <div class="col-md-12">
                <span>{{ __('Phone') }}:</span> <span class="order-detail-value">{{ $order->address->phone }} </span>
            </div>

            <div class="col-md-12">
                <span>{{ __('Address') }}:</span> <span
                    class="order-detail-value"> {{ $order->address->address }} </span>
            </div>

            <div class="col-md-12">
                <span>{{ __('City') }}:</span> <span
                    class="order-detail-value">{{ $order->address->city_name }} </span>
                <span>{{ __('State') }}:</span> <span
                    class="order-detail-value"> {{ $order->address->state_name }} </span>
                <span>{{ __('Country') }}:</span> <span
                    class="order-detail-value"> {{ $order->address->country_name }} </span>
            </div>

            <h5>{{ __('Order detail') }}</h5>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th class="text-center" style="width: 100px;">{{ __('Quantity') }}</th>
                            <th class="price text-end">{{ __('Total') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->products as $key => $orderProduct)
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
                                        'ec_products.status',
                                        'ec_products.order',
                                        'ec_products.created_at',
                                    ],
                                ]);

                            @endphp
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">
                                    <img src="{{ RvMedia::getImageUrl($product ? $product->image : null, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $orderProduct->product_name }}" width="50">
                                </td>
                                <td>
                                    {{ $orderProduct->product_name }} @if ($product && $product->sku) ({{ $product->sku }}) @endif
                                    @if ($product->is_variation)
                                        <p>
                                            <small>{{ $product->variation_attributes }}</small>
                                        </p>
                                    @endif

                                    @if (!empty($orderProduct->options) && is_array($orderProduct->options))
                                        @foreach($orderProduct->options as $option)
                                            @if (!empty($option['key']) && !empty($option['value']))
                                                <p class="mb-0"><small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small></p>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ format_price($orderProduct->price) }}</td>
                                <td class="text-center">{{ $orderProduct->qty }}</td>
                                <td class="money text-end">
                                    <strong>
                                        {{ format_price($orderProduct->price * $orderProduct->qty) }}
                                    </strong>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($order->shipment)
                <br>
                <h5>{{ __('Shipping Information:') }}</h5>
                <p><span class="d-inline-block">{{ __('Shipping Status') }}</span>: <strong class="d-inline-block text-info">{!! BaseHelper::clean($order->shipment->status->toHtml()) !!}</strong></p>
                @if ($order->shipment->shipping_company_name)
                    <p><span class="d-inline-block">{{ __('Shipping Company Name') }}</span>: <strong class="d-inline-block">{{ $order->shipment->shipping_company_name }}</strong></p>
                @endif
                @if ($order->shipment->tracking_id)
                    <p><span class="d-inline-block">{{ __('Tracking ID') }}</span>: <strong class="d-inline-block">{{ $order->shipment->tracking_id }}</strong></p>
                @endif
                @if ($order->shipment->tracking_link)
                    <p><span class="d-inline-block">{{ __('Tracking Link') }}</span>: <strong class="d-inline-block"><a
                                href="{{ $order->shipment->tracking_link }}" target="_blank">{{ $order->shipment->tracking_link }}</a></strong></p>
                @endif
                @if ($order->shipment->note)
                    <p><span class="d-inline-block">{{ __('Delivery Notes') }}</span>: <strong class="d-inline-block">{{ $order->shipment->note }}</strong></p>
                @endif
                @if ($order->shipment->estimate_date_shipped)
                    <p><span class="d-inline-block">{{ __('Estimate Date Shipped') }}</span>: <strong class="d-inline-block">{{ $order->shipment->estimate_date_shipped }}</strong></p>
                @endif
                @if ($order->shipment->date_shipped)
                    <p><span class="d-inline-block">{{ __('Date Shipped') }}</span>: <strong class="d-inline-block">{{ $order->shipment->date_shipped }}</strong></p>
                @endif
            @endif

            <br>

            <div class="col-md-12">
                @if ($order->isInvoiceAvailable())
                    <a href="{{ route('customer.print-order', $order->id) }}?type=print" class="btn-print" target="_blank">{{ __('Print invoice') }}</a>&nbsp;
                    <a href="{{ route('customer.print-order', $order->id) }}" class="btn-print">{{ __('Download invoice') }}</a>
                @endif
                @if ($order->canBeCanceled())
                    <a href="{{ route('customer.orders.cancel', $order->id) }}"
                       class="btn-print">{{ __('Cancel order') }}</a>
                @endif
                @if ($order->canBeReturned())
                    <a href="{{ route('customer.orders.return', $order->id) }}"
                       class="btn-print">{{ __('Return Product(s)') }}</a>
                @endif
            </div>
        </div>

@endsection
