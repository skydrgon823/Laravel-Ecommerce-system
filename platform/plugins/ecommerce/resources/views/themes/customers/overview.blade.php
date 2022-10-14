@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="customer-page-title">{{ __('Account information') }}</h2>
        </div>

        <div class="panel-body">
        <div class="well customer-help">
            <i class="fa fa-user"></i> {{ __('Name') }}: {{ auth('customer')->user()->name }}</div>

            <div class="well customer-help"><i class="fa fa-calendar"></i> {{ __('Date of birth') }}: {{ auth('customer')->user()->dob ? auth('customer')->user()->dob : 'N/A' }}</div>
            <div class="well customer-help"><i class="fa fa-envelope"></i> {{ __('Email') }}: {{ auth('customer')->user()->email }}</div>
            <div class="well customer-help"><i class="fa fa-phone"></i> {{ __('Phone') }}: {{ auth('customer')->user()->phone ? auth('customer')->user()->phone : 'N/A' }}</div>
        </div>
    </div>

@endsection
