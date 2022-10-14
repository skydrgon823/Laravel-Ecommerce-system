@extends(EcommerceHelper::viewPath('customers.master'))
@section('content')
    <div class="section-header">
        <h3>{{ SeoHelper::getTitle() }}</h3>
    </div>
    <div class="section-content">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Product Name') }}</th>
                    <th>{{ __('Times downloaded') }}</th>
                    <th>{{ __('Ordered at') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @if (count($orderProducts) > 0)
                    @foreach ($orderProducts as $orderProduct)
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
                            <td>
                                <img src="{{ RvMedia::getImageUrl($product->id ? $product->image : null, 'thumb', false, RvMedia::getDefaultImage()) }}" width="50" alt="{{ $orderProduct->product_name }}">
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
                            </td>
                            <td>
                                <span>{{ $orderProduct->times_downloaded }}</span>
                            </td>
                            <td>{{ $orderProduct->created_at->translatedFormat('M d, Y h:m') }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('customer.downloads.product', $orderProduct->id) }}">
                                    <i class="icon icon-download"></i>
                                    <span>{{ __('Download') }}</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">{{ __('No digital products!') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {!! $orderProducts->links() !!}
        </div>
    </div>
@endsection
