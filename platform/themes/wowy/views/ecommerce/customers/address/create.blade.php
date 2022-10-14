@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @include(Theme::getThemeNamespace() . '::views.ecommerce.customers.address.form', [
        'title' => __('Create Address'),
        'route' => 'customer.address.create',
        'address'   => app(\Botble\Ecommerce\Repositories\Interfaces\AddressInterface::class)->getModel(),
        'button'    => __('Add a new address')
    ])
@endsection
