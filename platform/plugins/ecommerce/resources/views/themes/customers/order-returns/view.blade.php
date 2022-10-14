@extends(EcommerceHelper::viewPath('customers.master'))
@section('content')
    <h2 class="customer-page-title">{{ __('Request Return Product(s) In Order') }}</h2>
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
            <h5>{{ __('Choose Product you want to return') }}</h5>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th class="text-center" style="width: 100px;">{{ __('Quantity') }}</th>
                            <th class="text-end">{{ __('Reason') }}</th>
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
                                <td class="text-center">
                                    <input type="checkbox" name="return_items[{{ $key }}].order_item_id"
                                                               value="{{$orderProduct->id}}"></td>
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
                                </td>

                                <td class="product-quantity" data-title="{{ __('Qty') }}">
                                    <div class="product-quantity">
                                        <span data-value="+" class="quantity-btn quantityPlus"></span>
                                        <input class="quantity input-lg" step="1" min="1" max="{{ $orderProduct->qty }}" title="{{ __('Qty') }}" value="{{ $orderProduct->qty }}" name="return_items[{{ $key }}][qty]" type="number" />
                                        <span data-value="-" class="quantity-btn quantityMinus"></span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <textarea class="form-control" name="return_items[{{ $key }}].reason">
                                    </textarea>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>

            <div class="col-md-12">
                @if ($order->canBeReturned())
                    <a href="{{ route('customer.orders.return', $order->id) }}" onclick="return confirm('{{ __('Are you sure?') }}')"
                       class="btn-print">{{ __('Send Return Request') }}</a>
                @endif
            </div>
        </div>

@endsection
