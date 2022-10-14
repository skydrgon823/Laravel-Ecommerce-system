@php
    Theme::layout('full-width');
@endphp

<section class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="login_wrap widget-taber-content p-30 background-white border-radius-10">
                    <div class="padding_eight_all bg-white">
                        <div class="heading_s1 mb-20">
                            <h3 class="mb-30">{{ __('Reset Password') }}</h3>
                        </div>

                        <form class="form--auth form--login" method="POST" action="{{ route('customer.password.reset.post') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form__content">
                                <div class="form-group">
                                    <label for="txt-email" class="required">{{ __('Email Address') }}</label>
                                    <input class="form-control" name="email" id="txt-email" type="email" value="{{ old('email') }}" placeholder="{{ __('Please enter your email address') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="txt-password" class="required">{{ __('Password') }}</label>
                                    <input class="form-control" type="password" name="password" id="txt-password" placeholder="{{ __('Please enter your password') }}">
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="txt-password-confirmation" class="required">{{ __('Password confirmation') }}</label>
                                    <input class="form-control" type="password" name="password_confirmation" id="txt-password-confirmation" placeholder="{{ __('Please enter your password confirmation') }}">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-fill-out btn-block hover-up">{{ __('Submit') }}</button>
                            </div>
                            @if (session('status'))
                                <div class="text-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
