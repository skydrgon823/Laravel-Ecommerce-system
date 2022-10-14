@if ($order)
    <div class="customer-order-detail">
        <div class="row">
            <div class="col-md-6">
                <h5>{{ __('Order information') }}</h5>
                <p>
                    <span>{{ __('Order number') }}: </span>
                    <strong>{{ get_order_code($order->id) }}</strong>
                </p>
                <p>
                    <span>{{ __('Time') }}: </span>
                    <strong>{{ $order->created_at->translatedFormat('h:m d/m/Y') }}</strong>
                </p>
                <p>
                    <span>{{ __('Order status') }}: </span>
                    <strong class="text-info">{{ $order->status->label() }}</strong>
                </p>
                <p>
                    <span>{{ __('Payment method') }}: </span>
                    <strong class="text-info">{{ $order->payment->payment_channel->label() }}</strong>
                </p>
                <p>
                    <span>{{ __('Payment status') }}: </span>
                    <strong class="text-info">{{ $order->payment->status->label() }}</strong>
                </p>
                @if ($order->description)
                    <p>
                        <span>{{ __('Note') }}: </span>
                        <strong class="text-warning"><i>{{ $order->description }}</i></strong>
                    </p>
                @endif
            </div>
            <div class="col-md-6">
                <h5>{{ __('Customer information') }}</h5>
                <p>
                    <span>{{ __('Full Name') }}: </span>
                    <strong>{{ $order->address->name }}</strong>
                </p>
                <p>
                    <span>{{ __('Phone') }}: </span>
                    <strong>{{ $order->address->phone }}</strong>
                </p>
                <p>
                    <span>{{ __('Address') }}: </span>
                    <strong> {{ $order->address->address }}</strong>
                </p>
                <p>
                    <span>{{ __('City') }}: </span>
                    <strong>{{ $order->address->city_name }}</strong>
                </p>
                <p>
                    <span>{{ __('State') }}: </span>
                    <strong> {{ $order->address->state_name }}</strong>
                </p>
                <p>
                    <span>{{ __('Country') }}: </span>
                    <strong> {{ $order->address->country_name }}</strong>
                </p>
                @if (EcommerceHelper::isZipCodeEnabled())
                    <p>
                        <span>{{ __('Zip code') }}: </span>
                        <strong> {{ $order->address->zip_code }}</strong>
                    </p>
                @endif
            </div>
        </div>
        <br>
        <h5>{{ __('Order detail') }}</h5>
        <div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">{{ __('Image') }}</th>
                        <th>{{ __('Product') }}</th>
                        <th class="text-center">{{ __('Amount') }}</th>
                        <th class="text-end" style="width: 100px">{{ __('Quantity') }}</th>
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
                                'include_out_of_stock_products' => true,
                            ]);
                        @endphp

                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">
                                <img
                                    src="{{ RvMedia::getImageUrl($product ? $product->image : null, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                    width="50" alt="{{ $orderProduct->product_name }}">
                            </td>
                            <td>
                                @if ($product)
                                    {{ $product->original_product->name }} @if ($product->sku)
                                        ({{ $product->sku }})
                                    @endif
                                    @if ($product->is_variation)
                                        <p class="mb-0">
                                            <small>
                                                @php $attributes = get_product_attributes($product->id) @endphp
                                                @if (!empty($attributes))
                                                    @foreach ($attributes as $attribute)
                                                        {{ $attribute->attribute_set_title }}
                                                        : {{ $attribute->title }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </small>
                                        </p>
                                    @endif
                                @else
                                    {{ $orderProduct->product_name }}
                                @endif

                                @if (!empty($orderProduct->options) && is_array($orderProduct->options))
                                    @foreach($orderProduct->options as $option)
                                        @if (!empty($option['key']) && !empty($option['value']))
                                            <p class="mb-0"><small>{{ $option['key'] }}:
                                                    <strong> {{ $option['value'] }}</strong></small></p>
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

            <p>
                <span>{{ __('Shipping fee') }}: </span>
                <strong>{{ format_price($order->shipping_amount) }}</strong>
            </p>

            <p>
                <span>{{ __('Total Amount') }}: </span>
                <strong>{{ format_price($order->amount) }}</strong>
            </p>
        </div>

        @if ($order->shipment->id)
            <br>
            <h5>{{ __('Shipping Information') }}: </h5>
            <p>
                <span class="d-inline-block">{{ __('Shipping Status') }}: </span>
                <strong
                    class="d-inline-block text-info">{!! BaseHelper::clean($order->shipment->status->toHtml()) !!}</strong>
            </p>
            @if ($order->shipment->shipping_company_name)
                <p>
                    <span class="d-inline-block">{{ __('Shipping Company Name') }}: </span>
                    <strong class="d-inline-block">{{ $order->shipment->shipping_company_name }}</strong>
                </p>
            @endif
            @if ($order->shipment->tracking_id)
                <p>
                    <span class="d-inline-block">{{ __('Tracking ID') }}: </span>
                    <strong class="d-inline-block">{{ $order->shipment->tracking_id }}</strong>
                </p>
            @endif
            @if ($order->shipment->tracking_link)
                <p>
                    <span class="d-inline-block">{{ __('Tracking Link') }}: </span>
                    <strong class="d-inline-block">
                        <a href="{{ $order->shipment->tracking_link }}"
                           target="_blank">{{ $order->shipment->tracking_link }}</a>
                    </strong>
                </p>
            @endif
            @if ($order->shipment->note)
                <p>
                    <span class="d-inline-block">{{ __('Delivery Notes') }}: </span>
                    <strong class="d-inline-block">{{ $order->shipment->note }}</strong>
                </p>
            @endif
            @if ($order->shipment->estimate_date_shipped)
                <p>
                    <span class="d-inline-block">{{ __('Estimate Date Shipped') }}: </span>
                    <strong class="d-inline-block">{{ $order->shipment->estimate_date_shipped }}</strong>
                </p>
            @endif
            @if ($order->shipment->date_shipped)
                <p>
                    <span class="d-inline-block">{{ __('Date Shipped') }}: </span>
                    <strong class="d-inline-block">{{ $order->shipment->date_shipped }}</strong>
                </p>
            @endif
        @endif
        @elseif (request()->input('order_id') || request()->input('email'))
            <p class="text-center text-danger">{{ __('Order not found!') }}</p>
@endif
