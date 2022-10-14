<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title', __('Checkout')) </title>

    @if (theme_option('favicon'))
        <link rel="shortcut icon" href="{{ RvMedia::getImageUrl(theme_option('favicon')) }}">
    @endif

    {!! Html::style('vendor/core/core/base/libraries/font-awesome/css/fontawesome.min.css') !!}
    {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme.css?v=1.2.0') !!}

    @if (BaseHelper::siteLanguageDirection() == 'rtl')
        {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme-rtl.css?v=1.2.0') !!}
    @endif

    {!! Html::style('vendor/core/core/base/libraries/toastr/toastr.min.css') !!}

    {!! Html::script('vendor/core/plugins/ecommerce/js/checkout.js?v=1.2.0') !!}

    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
        <script src="{{ asset('vendor/core/plugins/location/js/location.js') }}?v=1.2.0"></script>
    @endif

    {!! apply_filters('ecommerce_checkout_header', null) !!}
</head>
<body class="checkout-page" @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
    {!! apply_filters('ecommerce_checkout_body', null) !!}
    <div class="checkout-content-wrap">
        <div class="container">
            <div class="row">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('footer')

    {!! Html::script('vendor/core/plugins/ecommerce/js/utilities.js') !!}
    {!! Html::script('vendor/core/core/base/libraries/toastr/toastr.min.js') !!}

    <script type="text/javascript">
        window.messages = {
            error_header: '{{ __('Error') }}',
            success_header: '{{ __('Success') }}',
        }
    </script>

    @if (session()->has('success_msg') || session()->has('error_msg') || isset($errors))
        <script type="text/javascript">
            $(document).ready(function () {
                @if (session()->has('success_msg'))
                    MainCheckout.showNotice('success', '{{ session('success_msg') }}');
                @endif
                @if (session()->has('error_msg'))
                    MainCheckout.showNotice('error', '{{ session('error_msg') }}');
                @endif
                @if (isset($errors))
                    @foreach ($errors->all() as $error)
                        MainCheckout.showNotice('error', '{{ $error }}');
                    @endforeach
                @endif
            });
        </script>
    @endif

    {!! apply_filters('ecommerce_checkout_footer', null) !!}

</body>
</html>
