@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @php Theme::set('pageName', __('Return Product(s)')) @endphp
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">{{ __('Return Product(s)') }}</h4>
        </div>
        <div class="card-body">
            <div class="customer-order-detail">
                @include('plugins/ecommerce::themes.includes.order-return-detail')
            </div>
        </div>
    </div>
@endsection
