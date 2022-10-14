<section class="section--blog">
    <div class="section__content">
        <section class="section--auth">
            <form class="form--auth" method="GET" action="{{ route('public.orders.tracking') }}">
                <div class="form__header">
                    <h3>{{ __('Order tracking') }}</h3>
                    <p>{{ __('Tracking your order status') }}</p>
                </div>
                <div class="form__content">
                    <div class="form-group mb-3">
                        <label for="txt-order-id">{{ __('Order ID') }}<sup>*</sup></label>
                        <input class="form-control" name="order_id" id="txt-order-id" type="text" value="{{ old('order_id', request()->input('order_id')) }}" placeholder="{{ __('Order ID') }}">
                        @if ($errors->has('order_id'))
                            <span class="text-danger">{{ $errors->first('order_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt-email">{{ __('Email Address') }}<sup>*</sup></label>
                        <input class="form-control" name="email" id="txt-email" type="email" value="{{ old('email', request()->input('email')) }}" placeholder="{{ __('Please enter your email address') }}">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form__actions">
                        <button type="submit" class="btn--custom btn--rounded btn--outline">{{ __('Find') }}</button>
                    </div>
                </div>
            </form>
            @include('plugins/ecommerce::themes.includes.order-tracking-detail')
        </section>
    </div>
</section>
