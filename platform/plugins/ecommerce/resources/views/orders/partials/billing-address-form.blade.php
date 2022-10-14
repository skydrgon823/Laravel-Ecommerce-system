<div class="customer-billing-address-form">

    <div class="form-group mb-3">
        <input type="hidden" name="billing_address_same_as_shipping_address" value="0">
        <input type="checkbox" name="billing_address_same_as_shipping_address" value="1" id="billing_address_same_as_shipping_address" @if (old('billing_address_same_as_shipping_address', Arr::get($sessionCheckoutData, 'billing_address_same_as_shipping_address', true))) checked @endif>
        <label for="billing_address_same_as_shipping_address" class="control-label" style="padding-left: 5px">{{ __('Same as shipping information') }}</label>
    </div>

    <div class="billing-address-form-wrapper" @if (old('billing_address_same_as_shipping_address', Arr::get($sessionCheckoutData, 'billing_address_same_as_shipping_address', true))) style="display: none" @endif>
        <div class="row">
            <div class="col-12">
                <div class="form-group mb-3 @if ($errors->has('billing_address.name')) has-error @endif">
                    <input type="text" name="billing_address[name]" id="billing-address-name" placeholder="{{ __('Full Name') }}" class="form-control checkout-input"
                           value="{{ old('billing_address.name', Arr::get($sessionCheckoutData, 'billing_address.name')) }}">
                        {!! Form::error('billing_address.name', $errors) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="form-group  @if ($errors->has('billing_address.email')) has-error @endif">
                    <input type="text" name="billing_address[email]" id="billing-address-email" placeholder="{{ __('Email') }}" class="form-control checkout-input" value="{{ old('billing_address.email', Arr::get($sessionCheckoutData, 'billing_address.email')) }}">
                    {!! Form::error('billing_address.email', $errors) !!}
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="form-group  @if ($errors->has('billing_address.phone')) has-error @endif">
                    <input type="text" name="billing_address[phone]" id="billing-address-phone" placeholder="{{ __('Phone') }} {{ EcommerceHelper::isPhoneFieldOptionalAtCheckout() ? __('(optional)') : '' }}" class="form-control checkout-input checkout-input" value="{{ old('billing_address.phone', Arr::get($sessionCheckoutData, 'billing_address.phone')) }}">
                    {!! Form::error('billing_address.phone', $errors) !!}
                </div>
            </div>
        </div>

        <div class="row">
            @if (EcommerceHelper::isUsingInMultipleCountries())
                <div class="col-12">
                    <div class="form-group mb-3 @if ($errors->has('billing_address.country')) has-error @endif">
                        <div class="select--arrow">
                            <select name="billing_address[country]" class="form-control checkout-input" id="billing-address-country" data-type="country">
                                @foreach(EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                                    <option value="{{ $countryCode }}" @if (old('billing_address.country', Arr::get($sessionCheckoutData, 'billing_address.country')) == $countryCode) selected @endif>{{ $countryName }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-angle-down"></i>
                        </div>
                        {!! Form::error('billing_address.country', $errors) !!}
                    </div>
                </div>
            @else
                <input type="hidden" name="billing_address[country]" id="billing-address-country" value="{{ EcommerceHelper::getFirstCountryId() }}">
            @endif

            <div class="col-sm-6 col-12">
                <div class="form-group mb-3 @if ($errors->has('billing_address.state')) has-error @endif">
                    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                        <div class="select--arrow">
                            <select name="billing_address[state]" class="form-control checkout-input" id="billing-address-state" data-type="state" data-url="{{ route('ajax.states-by-country') }}">
                                <option value="">{{ __('Select state...') }}</option>
                                @if (old('billing_address.country', Arr::get($sessionCheckoutData, 'billing_address.country')) || !EcommerceHelper::isUsingInMultipleCountries())
                                    @foreach(EcommerceHelper::getAvailableStatesByCountry(old('billing_address.country', Arr::get($sessionCheckoutData, 'billing_address.country'))) as $stateId => $stateName)
                                        <option value="{{ $stateId }}" @if (old('billing_address.state', Arr::get($sessionCheckoutData, 'billing_address.state')) == $stateId) selected @endif>{{ $stateName }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    @else
                        <input id="billing-address-state" type="text" class="form-control checkout-input" placeholder="{{ __('State') }}" name="billing_address[state]" value="{{ old('billing_address.state', Arr::get($sessionCheckoutData, 'billing_address.state')) }}">
                    @endif
                    {!! Form::error('billing_address.state', $errors) !!}
                </div>
            </div>

            <div class="col-sm-6 col-12">
                <div class="form-group  @if ($errors->has('billing_address.city')) has-error @endif">
                    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                        <div class="select--arrow">
                            <select name="billing_address[city]" class="form-control checkout-input" id="billing-address-city" data-type="city" data-url="{{ route('ajax.cities-by-state') }}">
                                <option value="">{{ __('Select city...') }}</option>
                                @if (old('billing_address.state', Arr::get($sessionCheckoutData, 'billing_address.state')))
                                    @foreach(EcommerceHelper::getAvailableCitiesByState(old('billing_address.state', Arr::get($sessionCheckoutData, 'billing_address.state'))) as $cityId => $cityName)
                                        <option value="{{ $cityId }}" @if (old('billing_address.city', Arr::get($sessionCheckoutData, 'billing_address.city')) == $cityId) selected @endif>{{ $cityName }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    @else
                        <input id="billing-address-city" type="text" class="form-control checkout-input" placeholder="{{ __('City') }}" name="billing_address[city]" value="{{ old('billing_address.city', Arr::get($sessionCheckoutData, 'billing_address.city')) }}">
                    @endif
                    {!! Form::error('billing_address.city', $errors) !!}
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mb-3 @if ($errors->has('billing_address.address')) has-error @endif">
                    <input id="billing-address-address" type="text" class="form-control checkout-input" placeholder="{{ __('Address') }}" name="billing_address[address]" value="{{ old('billing_address.address', Arr::get($sessionCheckoutData, 'billing_address.address')) }}">
                    {!! Form::error('billing_address.address', $errors) !!}
                </div>
            </div>

            @if (EcommerceHelper::isZipCodeEnabled())
                <div class="col-12">
                    <div class="form-group mb-3 @if ($errors->has('billing_address.zip_code')) has-error @endif">
                        <input id="billing-address-zip_code" type="text" class="form-control checkout-input" placeholder="{{ __('Zip code') }}" name="billing_address[zip_code]" value="{{ old('billing_address.zip_code', Arr::get($sessionCheckoutData, 'billing_address.zip_code')) }}">
                        {!! Form::error('billing_address.zip_code', $errors) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
