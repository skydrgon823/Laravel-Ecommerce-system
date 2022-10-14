@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="customer-page-title">{{ __('Change password') }}</h2>
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => 'customer.post.change-password', 'method' => 'post']) !!}
            <div class="input-group @if ($errors->has('old_password')) has-error @endif">
                <span class="input-group-prepend">{{ __('Old password:') }}</span>
                <input type="password" class="form-control" name="old_password" id="old_password"
                       placeholder="{{ __('Current Password') }}">
                {!! Form::error('old_password', $errors) !!}
            </div>
            <div class="input-group @if ($errors->has('password')) has-error @endif">
                <span class="input-group-prepend">{{ __('New password:') }}</span>
                <input type="password" class="form-control" name="password" id="password"
                       placeholder="{{ __('New Password') }}">
                {!! Form::error('password', $errors) !!}
            </div>
            <div class="input-group @if ($errors->has('password_confirmation')) has-error @endif">
                <span class="input-group-prepend">{{ __('Password confirmation:') }}</span>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                       placeholder="{{ __('Password Confirmation') }}">
                {!! Form::error('password_confirmation', $errors) !!}
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Change password') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
