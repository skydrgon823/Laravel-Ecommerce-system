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
                            <h3 class="mb-20">{{ __('Register') }}</h3>
                            <p>{{ __('Please fill in the information below') }}</p>
                        </div>

                        <form class="form--auth" method="POST" action="{{ route('customer.register.post') }}">
                            @csrf
                            <div class="form__content">
                                <div class="form-group">
                                    <label for="txt-name" class="required">{{ __('Name') }}</label>
                                    <input class="form-control" name="name" id="txt-name" type="text" value="{{ old('name') }}" placeholder="{{ __('Please enter your name') }}">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
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

				@if (is_plugin_active('captcha') && setting('enable_captcha') && get_ecommerce_setting('enable_recaptcha_in_register_page', 0))
                        	    <div class="form-group">
                        	       {!! Captcha::display() !!}
            		            </div>
	                        @endif

                                <div class="form-group">
                                    <div class="ps-checkbox">

                                    </div>
                                    @if ($errors->has('agree_terms_and_policy'))
                                        <span class="text-danger">{{ $errors->first('agree_terms_and_policy') }}</span>
                                    @endif
                                </div>

                                <div class="login_footer form-group">
                                    <div class="chek-form">
                                        <div class="custome-checkbox">
                                            <input type="hidden" name="agree_terms_and_policy" value="0">
                                            <input class="form-check-input" type="checkbox" name="agree_terms_and_policy" id="agree-terms-and-policy" value="1" @if (old('agree_terms_and_policy') == 1) checked @endif>
                                            <label class="form-check-label" for="agree-terms-and-policy"><span>{{ __('I agree to terms & Policy.') }}</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-fill-out btn-block hover-up">{{ __('Register') }}</button>
                                </div>

                                <br>
                                <p>{{ __('Have an account already?') }} <a href="{{ route('customer.login') }}" class="d-inline-block">{{ __('Login') }}</a></p>

                                <div class="text-left">
                                    {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\Ecommerce\Models\Customer::class) !!}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
