<div class="form-group">
    <label class="control-label">{{ __('Is autoplay?') }}</label>
    <div class="ui-select-wrapper form-group">
        <select name="is_autoplay" class="ui-select">
            <option value="no"
                    @if (Arr::get($attributes, 'is_autoplay', 'yes') == 'no') selected @endif>{{ trans('core/base::base.no') }}</option>
            <option value="yes"
                    @if (Arr::get($attributes, 'is_autoplay', 'yes') == 'yes') selected @endif>{{ trans('core/base::base.yes') }}</option>
        </select>
        <svg class="svg-next-icon svg-next-icon-size-16">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
        </svg>
    </div>
</div>

<div class="form-group">
    <label class="control-label">{{ __('Autoplay speed (if autoplay enabled)') }}</label>
    {!! Form::customSelect('autoplay_speed', theme_get_autoplay_speed_options(), Arr::get($attributes, 'autoplay_speed', 3000)) !!}
</div>
