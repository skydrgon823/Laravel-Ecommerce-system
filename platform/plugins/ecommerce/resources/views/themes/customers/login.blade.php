<section class="content-page">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="form-border-box">

                    <form method="POST" action="{{ route('customer.login.post') }}">
                        @csrf
                        <h2 class="normal"><span>{{ __('Login') }}</span></h2>
                        @if (isset($errors) && $errors->has('confirmation'))
                            <div class="alert alert-danger">
                                <span>{!! $errors->first('confirmation') !!}</span>
                            </div>
                            <br>
                        @endif

                        <div class="form-field-wrapper form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label>{{ __('Email') }} <span class="required">*</span></label>
                            <input id="email" class="input-md form-full-width" name="email"
                                   placeholder="{{ __('Email') }}" size="30" aria-required="true" required type="email">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                            @endif
                        </div>

                        <div class="form-field-wrapper form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label>{{ __('Password') }} <span class="required">*</span></label>
                            <input id="password" class="input-md form-full-width" name="password"
                                   placeholder="{{ __('Password') }}" size="30" aria-required="true" required
                                   type="password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                            @endif
                        </div>

                        <div class="form-field-wrapper">
                            <button type="submit" class="submit btn btn-md btn-black">
                                {{ __('Login') }}
                            </button>
                            <div class="checkbox float-end">
                                <a href="{{ route('customer.password.reset') }}">
                                    {{ __('Forgot password?') }}
                                </a>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-border-box">
                    <form>
                        <h2 class="normal"><span>{{ __('You are a new customer?') }}</span></h2>
                        <p>{{ __('Register here') }}</p>
                        <div class="form-field-wrapper">
                            <a class="submit btn btn-md btn-color" href="{{ route('customer.register') }}">
                                {{ __('Register a new account') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center">
                {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\Ecommerce\Models\Customer::class) !!}
            </div>
        </div>
    </div>
</section>
