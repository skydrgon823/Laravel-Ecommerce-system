<div class="form-group">
    <label class="control-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="form-group">
    <label class="control-label">{{ __('Show sale popup?') }}</label>
    {!! Form::customSelect('show_popup', ['yes' => trans('core/setting::setting.general.yes'), 'no' => trans('core/setting::setting.general.no')], Arr::get($attributes, 'show_popup', 'yes')) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ __('Limit') }}</label>
    <input type="number" name="limit" value="{{ Arr::get($attributes, 'limit', 2) }}" class="form-control" placeholder="{{ __('Limit') }}">
</div>
