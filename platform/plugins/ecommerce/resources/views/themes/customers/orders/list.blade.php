@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')

    <h2 class="customer-page-title">{{ __('Orders') }}</h2>

    <div class="customer-list-order">
        <table class="table  table-hover">
            <thead>
            <tr class="success">
                <th>{{ __('Order number') }}</th>
                <th>{{ __('Created at') }}</th>
                <th>{{ __('Payment method') }}</th>
                <th>{{ __('Status') }}</th>
                <th></th>
            </tr></thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>

                        <td>#{{ config('plugins.ecommerce.order.order_code_prefix') }}{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('h:m d/m/Y') }}</td>
                        <td>{{ $order->payment->payment_channel->label() }}</td>
                        <td>{{ $order->status->label() }}</td>

                        <td>
                            <a class="btn btn-info btn-order-detail" href="{{ route('customer.orders.view', $order->id) }}">{{ __('View') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {!! $orders->links() !!}
        </div>

    </div>
@endsection
