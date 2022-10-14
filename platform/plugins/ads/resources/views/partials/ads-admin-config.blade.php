<div class="form-group mb-3">
    <label class="control-label">{{ trans('plugins/ads::ads.select_ad') }}</label>
    {!! Form::customSelect('key', $ads, Arr::get($attributes, 'key')) !!}
</div>
