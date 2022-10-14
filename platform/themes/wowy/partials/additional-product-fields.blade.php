<div class="form-group">
    <label for="layout" class="control-label">{{ __('Layout') }}</label>
    {!! Form::customSelect('layout', get_product_single_layouts(), $layout, ['class' => 'form-control', 'id' => 'layout']) !!}
</div>
