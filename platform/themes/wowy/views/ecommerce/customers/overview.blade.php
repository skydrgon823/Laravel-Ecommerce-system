@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Hello :name!', ['name' => auth('customer')->user()->name]) }} </h5>
        </div>
        <div class="card-body">
            <p>
                {!! BaseHelper::clean(__('From your account dashboard. you can easily check &amp; view your <a href=":order">recent orders</a>', [
                    'order' => route('customer.orders'),
                ])) !!},

                {!! BaseHelper::clean(__('manage your <a href=":address">shipping and billing addresses</a> and <a href=":profile">edit your password and account details.</a>', [
                    'profile' => route('customer.edit-account'),
                    'address' => route('customer.address'),
                ])) !!}
            </p>
        </div>
    </div>
@endsection
