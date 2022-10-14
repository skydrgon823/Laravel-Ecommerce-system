@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Your Orders') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID number') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ get_order_code($order->id) }}</td>
                                <td>{{ $order->created_at->format('Y/m/d h:m') }}</td>
                                <td>{{ __(':price for :total item(s)', ['price' => $order->amount_format, 'total' => $order->products_count]) }}</td>
                                <td>{{ $order->status->label() }}</td>
                                <td>
                                    <a class="btn-small d-block" href="{{ route('customer.orders.view', $order->id) }}">{{ __('View') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="5">{{ __('No orders found!') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {!! $orders->links(Theme::getThemeNamespace() . '::partials.custom-pagination') !!}
            </div>
        </div>
    </div>
@endsection
