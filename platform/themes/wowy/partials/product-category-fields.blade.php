<div class="form-group">
    <label class="control-label" for="icon">{{ __('Font Icon') }}</label>
    {!! Form::themeIcon('icon', $icon, ['class' =>'form-control', 'id' => 'icon-select']) !!}
</div>

<div class="form-group">
    <label class="control-label" for="icon_image">{{ __('Icon Image (It will replace Font Icon if it is present)') }}</label>
    {!! Form::mediaImage('icon_image', $iconImage) !!}
</div>
