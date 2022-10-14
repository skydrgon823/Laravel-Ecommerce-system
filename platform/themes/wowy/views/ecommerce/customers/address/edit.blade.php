@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')

@section('content')
    @includeIf(Theme::getThemeNamespace() . '::views.ecommerce.customers.address.form', [
        'title'     => __('Edit Address #:id', ['id' => $address->id]),
        'route'     => ['customer.address.edit', $address->id],
        'address'   => $address,
        'button'    => __('Update')
    ])
@endsection
