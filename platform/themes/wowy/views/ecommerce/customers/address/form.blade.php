<div class="card">
    <div class="card-header">
        <h5>{{ $title }}</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => $route]) !!}
            <div class="form-group">
                <label for="name" class="required">{{ __('Full Name') }}:</label>
                <input id="name"
                    type="text"
                    class="form-control square"
                    name="name"
                    value="{{ old('name', $address->id ? $address->name : auth('customer')->user()->name) }}"
                    placeholder="{{ __('Enter your full name') }}"
                    required>
                {!! Form::error('name', $errors) !!}
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}:</label>
                <input id="email"
                    type="email"
                    class="form-control square"
                    name="email"
                    value="{{ old('email', $address->id ? $address->email : auth('customer')->user()->email) }}"
                    placeholder="your-email@domain.com">
                {!! Form::error('email', $errors) !!}
            </div>

            <div class="form-group">
                <label for="phone" class="required">{{ __('Phone') }}:</label>
                <input id="phone"
                    type="text"
                    class="form-control square"
                    name="phone"
                    value="{{ old('phone', $address->id ? $address->phone : auth('customer')->user()->phone) }}"
                    placeholder="0123456789"
                    required>
                {!! Form::error('phone', $errors) !!}
            </div>

            @if (EcommerceHelper::isUsingInMultipleCountries())
                <div class="form-group @if ($errors->has('country')) has-error @endif">
                    <label for="country" class="required">{{ __('Country') }}:</label>
                    <select name="country" required class="form-control square" id="country" data-type="country">
                        @foreach(['' => __('Select country...')] + EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                            <option value="{{ $countryCode }}" @if (old('country', $address->country) == $countryCode) selected @endif>{{ $countryName }}</option>
                        @endforeach
                    </select>
                    {!! Form::error('country', $errors) !!}
                </div>
            @else
                <input type="hidden" name="country" value="{{ EcommerceHelper::getFirstCountryId() }}">
            @endif

            <div class="form-group @if ($errors->has('state')) has-error @endif">
                <label for="state" class="required">{{ __('State') }}:</label>
                @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                    <select name="state" class="form-control" id="state" data-type="state" data-placeholder="{{ __('Select state...') }}" data-url="{{ route('ajax.states-by-country') }}">
                        <option value="">{{ __('Select state...') }}</option>
                        @if (old('country', $address->country) || !EcommerceHelper::isUsingInMultipleCountries())
                            @foreach(EcommerceHelper::getAvailableStatesByCountry(old('country', $address->country)) as $stateId => $stateName)
                                <option value="{{ $stateId }}" @if (old('state', $address->state) == $stateId) selected @endif>{{ $stateName }}</option>
                            @endforeach
                        @endif
                    </select>
                @else
                    <input id="state"
                        type="text"
                        class="form-control square"
                        name="state"
                        value="{{ old('state', $address->state) }}"
                        required
                        placeholder="{{ __('Enter your state') }}">
                @endif
                {!! Form::error('state', $errors) !!}
            </div>

            <div class="form-group @if ($errors->has('city')) has-error @endif">
                <label for="city" class="required">{{ __('City') }}:</label>
                @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                    <select name="city" class="form-control" id="city" data-type="city" data-placeholder="{{ __('Select city...') }}" data-url="{{ route('ajax.cities-by-state') }}">
                        <option value="">{{ __('Select city...') }}</option>
                        @if (old('state', $address->state))
                            @foreach(EcommerceHelper::getAvailableCitiesByState(old('state', $address->state)) as $cityId => $cityName)
                                <option value="{{ $cityId }}" @if (old('city', $address->city) == $cityId) selected @endif>{{ $cityName }}</option>
                            @endforeach
                        @endif
                    </select>
                @else
                    <input id="city"
                        type="text"
                        class="form-control square"
                        name="city"
                        value="{{ old('city', $address->city) }}"
                        required
                        placeholder="{{ __('Enter your city') }}">
                @endif
                {!! Form::error('city', $errors) !!}
            </div>

            <div class="form-group">
                <label for="address" class="required">{{ __('Address') }}:</label>
                <input id="address"
                    type="text"
                    class="form-control square"
                    name="address"
                    value="{{ old('address', $address->address) }}"
                    required
                    placeholder="{{ __('Enter your address') }}">
                {!! Form::error('address', $errors) !!}
            </div>

            @if (EcommerceHelper::isZipCodeEnabled())
                <div class="form-group">
                    <label for="zip_code" class="required">{{ __('Zip code') }}:</label>
                    <input id="zip_code"
                        type="text"
                        class="form-control"
                        name="zip_code"
                        value="{{ old('zip_code', $address->zip_code) }}"
                        required
                        placeholder="{{ __('Enter your zip code') }}">
                    {!! Form::error('zip_code', $errors) !!}
                </div>
            @endif

            <div class="form-group">
                <div class="custome-checkbox">
                    <input class="form-check-input"
                        @if (old('is_default', $address->is_default)) checked @endif
                        type="checkbox"
                        name="is_default"
                        value="1"
                        id="is_default">
                    <label for="is_default" class="form-check-label">
                        <span>{{ __('Use this address as default.') }}</span>
                    </label>
                </div>
                {!! Form::error('is_default', $errors) !!}
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-fill-out submit">{{ $button }}</button>
            </div>
        {!! Form::close() !!}
    </div>
</div>
