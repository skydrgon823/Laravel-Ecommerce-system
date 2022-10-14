<div class="form-group">
    <label class="control-label">{{ __('Product category ID') }}</label>
    {!! Form::customSelect('category_id', $categories, Arr::get($attributes, 'category_id')) !!}
</div>
