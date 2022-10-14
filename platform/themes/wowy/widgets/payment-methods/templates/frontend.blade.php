<div class="col-lg-4">
    <h5 class="widget-title mb-30 wow fadeIn animated">{!! BaseHelper::clean($config['name'] ?: __('Payments')) !!}</h5>
    <div class="row">
        <div class="col-md-4 col-lg-12">
            <p class="mb-20 wow fadeIn animated mt-md-3">{!! BaseHelper::clean($config['description'] ?: __('Secured Payment Gateways')) !!}</p>
            @if ($config['image'] || theme_option('payment_methods'))
                <img class="wow fadeIn animated" src="{{ RvMedia::getImageUrl($config['image'] ?: theme_option('payment_methods')) }}" alt="{{ __('Payment methods') }}">
            @endif
        </div>
    </div>
</div>
