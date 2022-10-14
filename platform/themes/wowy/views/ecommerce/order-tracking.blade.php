<section class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="login_wrap widget-taber-content p-30 background-white border-radius-10">
                    <div class="padding_eight_all bg-white">
                        <div class="heading_s1 mb-20 text-center">
                            <h3 class="mb-20">{{ __('Order tracking') }}</h3>
                            <p>{{ __('Tracking your order status') }}</p>
                        </div>
                        <form method="GET" action="{{ route('public.orders.tracking') }}">
                            <div class="form-group">
                                <label for="txt-order-id">{{ __('Order ID') }}<sup>*</sup></label>
                                <input class="form-control" name="order_id" id="txt-order-id" type="text" value="{{ old('order_id', request()->input('order_id')) }}" placeholder="{{ __('Order ID') }}">
                                @if ($errors->has('order_id'))
                                    <span class="text-danger">{{ $errors->first('order_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="txt-email">{{ __('Email Address') }}<sup>*</sup></label>
                                <input class="form-control" name="email" id="txt-email" type="email" value="{{ old('email', request()->input('email')) }}" placeholder="{{ __('Your Email') }}">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group submit">
                                <button class="btn btn-fill-out btn-block hover-up" type="submit">{{ __('Find') }}</button>
                            </div>
                    </form>

                @if ($order)
                    <div class="customer-order-detail" style="margin-top: 60px">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>{{ __('Order information') }}</h5>
                                <p>
                                    <span>{{ __('Order number') }}:</span>
                                    <strong>{{ get_order_code($order->id) }}</strong>
                                </p>
                                <p>
                                    <span>{{ __('Time') }}:</span> <strong>{{ $order->created_at->format('h:m d/m/Y') }}</strong>
                                </p>
                                <p>
                                    <span>{{ __('Order status') }}:</span> <strong class="text-info">{{ $order->status->label() }}</strong>
                                </p>

                                <p>
                                    <span>{{ __('Payment method') }}:</span> <strong class="text-info">{{ $order->payment->payment_channel->label() }}</strong>
                                </p>

                                <p>
                                    <span>{{ __('Payment status') }}:</span> <strong class="text-info">{{ $order->payment->status->label() }}</strong>
                                </p>
                                @if ($order->description)
                                    <p>
                                        <span>{{ __('Note') }}:</span> <strong class="text-warning"><i>{{ $order->description }}</i></strong>
                                    </p>
                                @endif

                            </div>
                            <div class="col-md-6 customer-information-box text-right">
                                <h5>{{ __('Customer information') }}</h5>

                                <p>
                                    <span>{{ __('Full Name') }}:</span> <strong>{{ $order->address->name }} </strong>
                                </p>

                                <p>
                                    <span>{{ __('Phone') }}:</span> <strong>{{ $order->address->phone }} </strong>
                                </p>

                                <p>
                                    <span>{{ __('Address') }}:</span> <strong> {{ $order->address->address }} </strong>
                                </p>

                                <p>
                                    <span>{{ __('City') }}:</span> <strong>{{ $order->address->city }} </strong>
                                </p>
                                <p>
                                    <span>{{ __('State') }}:</span> <strong> {{ $order->address->state }} </strong>
                                </p>
                                @if (EcommerceHelper::isUsingInMultipleCountries())
                                    <p>
                                        <span>{{ __('Country') }}:</span> <strong> {{ $order->address->country_name }} </strong>
                                    </p>
                                @endif
                                @if (EcommerceHelper::isZipCodeEnabled())
                                    <p>
                                        <span>{{ __('Zip code') }}:</span> <strong> {{ $order->address->zip_code }} </strong>
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
                                        <th class="text-right" style="width: 100px">{{ __('Quantity') }}</th>
                                        <th class="price text-right">{{ __('Total') }}</th>
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
                                                <img src="{{ RvMedia::getImageUrl($product ? $product->image : null, 'thumb', false, RvMedia::getDefaultImage()) }}" width="50" alt="{{ $orderProduct->product_name }}">
                                            </td>
                                            <td>
                                                @if ($product)
                                                    {{ $product->original_product->name }} @if ($product->sku) ({{ $product->sku }}) @endif
                                                    @if ($product->is_variation)
                                                        <p class="mb-0">
                                                            <small>
                                                                @php $attributes = get_product_attributes($product->id) @endphp
                                                                @if (!empty($attributes))
                                                                    @foreach ($attributes as $attribute)
                                                                        {{ $attribute->attribute_set_title }}: {{ $attribute->title }}@if (!$loop->last), @endif
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
                                                            <p class="mb-0"><small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small></p>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ format_price($orderProduct->price, $orderProduct->currency) }}</td>
                                            <td class="text-center">{{ $orderProduct->qty }}</td>
                                            <td class="money text-right">
                                                <strong>
                                                    {{ format_price($orderProduct->price * $orderProduct->qty, $orderProduct->currency) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <p>
                                <span>{{ __('Shipping fee') }}:</span> <strong>  {{ format_price($order->shipping_amount) }} </strong>
                            </p>

                            @if (EcommerceHelper::isTaxEnabled())
                                <p>
                                    <span>{{ __('Tax') }}:</span> <strong> {{ format_price($order->tax_amount) }} </strong>
                                </p>
                            @endif

                            <p>
                                <span>{{ __('Discount') }}: </span> <strong> {{ format_price($order->discount_amount) }}</strong>
                                @if ($order->discount_amount)
                                    @if ($order->coupon_code)
                                        ({!! __('Coupon code: ":code"', ['code' => Html::tag('strong', $order->coupon_code)->toHtml()]) !!})
                                    @elseif ($order->discount_description)
                                        ({{ $order->discount_description }})
                                    @endif
                                @endif
                            </p>

                            <p>
                                <span>{{ __('Total Amount') }}:</span> <strong> {{ format_price($order->amount) }} </strong>
                            </p>
                        </div>
                        @if ($order->shipment->id)
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
                        @elseif (request()->input('order_id') || request()->input('email'))
                            <p class="text-center text-danger mt-40">{{ __('Order not found!') }}</p>
                        @endif
                    </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</section>
