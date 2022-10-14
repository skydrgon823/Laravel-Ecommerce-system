@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Account Details') }}</h5>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'customer.edit-account']) !!}
                <div class="row">
                    <div class="form-group col-md-12 @if ($errors->has('name')) has-error @endif">
                        <label class="required" for="name">{{ __('Full Name') }}:</label>
                        <input required class="form-control square" name="name" type="text" id="name" value="{{ auth('customer')->user()->name }}">
                        {!! Form::error('name', $errors) !!}
                    </div>
                    <div class="form-group col-md-12 @if ($errors->has('dob')) has-error @endif">
                        <label for="date_of_birth">{{ __('Date of birth') }}:</label>
                        <input id="date_of_birth" type="text" class="form-control square" name="dob" placeholder="Y-m-d" value="{{ auth('customer')->user()->dob }}">
                        {!! Form::error('name', $errors) !!}
                    </div>
                    <div class="form-group col-md-12">
                        <label for="email">{{ __('Email') }}:</label>
                        <input id="email" type="text" class="form-control" disabled="disabled" value="{{ auth('customer')->user()->email }}" name="email">
                    </div>
                    <div class="form-group col-md-12 @if ($errors->has('phone')) has-error @endif">
                        <label for="phone">{{ __('Phone') }}:</label>
                        <input type="text" class="form-control square" name="phone" id="phone" placeholder="{{ __('Phone') }}" value="{{ auth('customer')->user()->phone }}">
                        {!! Form::error('name', $errors) !!}
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-fill-out submit">{{ __('Update') }}</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
