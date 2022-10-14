<div class="form-group">
    <label for="simple_slider_style" class="control-label">{{ __('Style') }}</label>
    {!! Form::customSelect('simple_slider_style', get_simple_slider_styles(), $style, ['class' => 'form-control', 'id' => 'simple_slider_style']) !!}
</div>
