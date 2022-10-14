@extends('plugins/ecommerce::orders.master')
@section('title')
    {{ __('Order successfully. Order number :id', ['id' => get_order_code($order->id)]) }}
@stop
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-12 left">
                @include('plugins/ecommerce::orders.partials.logo')

                <div class="thank-you">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <div class="d-inline-block">
                        <h3 class="thank-you-sentence">
                            {{ __('Your order is successfully placed') }}
                        </h3>
                        <p>{{ __('Thank you for purchasing our products!') }}</p>
                    </div>
                </div>

                @include('plugins/ecommerce::orders.thank-you.customer-info', compact('order'))

                <a href="{{ route('public.index') }}" class="btn payment-checkout-btn"> {{ __('Continue shopping') }} </a>
            </div>
            <!---------------------- start right column ------------------>
            <div class="col-lg-5 col-md-6 d-none d-md-block right">

                @include('plugins/ecommerce::orders.thank-you.order-info')

                <hr>

                <!-- total info -->
                @include('plugins/ecommerce::orders.thank-you.total-info', ['order' => $order])
            </div>
        </div>
    </div>
@stop
