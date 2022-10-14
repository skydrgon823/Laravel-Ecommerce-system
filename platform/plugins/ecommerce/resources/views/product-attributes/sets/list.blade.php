@if (!$isNotDefaultLanguage)
    <script id="product_attribute_template" type="text/x-custom-template">
        <li data-id="__id__" class="clearfix">
            <div class="swatch-is-default">
                <input type="radio" name="related_attribute_is_default" value="__position__" __checked__>
            </div>
            <div class="swatch-title">
                <input type="text" class="form-control" value="__title__">
            </div>
            <div class="swatch-slug">
                <input type="text" class="form-control" value="__slug__">
            </div>
            <div class="swatch-value">
                <input type="text" class="form-control input-color-picker" value="__color__">
            </div>
            <div class="swatch-image">
                <div class="image-box-container">
                    @include('plugins/ecommerce::components.form.image', [
                        'name' => '',
                        'value' => '__image__',
                        'thumb' => RvMedia::getDefaultImage(false),
                    ])
                </div>
            </div>
            <div class="remove-item"><a href="#" class="font-red"><i class="fa fa-trash"></i></a></div>
        </li>
    </script>
    <textarea name="deleted_attributes" id="deleted_attributes" class="hidden"></textarea>
@endif

<textarea name="attributes" id="attributes" class="hidden">{!! json_encode($attributes) !!}</textarea>
<div class="swatches-container">
    <div class="header clearfix">
        @if (!$isNotDefaultLanguage)
            <div class="swatch-is-default">
                {{ trans('plugins/ecommerce::product-attribute-sets.is_default') }}
            </div>
        @endif
        <div class="swatch-title text-start">
            {{ trans('plugins/ecommerce::product-attribute-sets.title') }}
        </div>
        @if (!$isNotDefaultLanguage)
            <div class="swatch-slug">
                {{ trans('plugins/ecommerce::product-attribute-sets.slug') }}
            </div>
            <div class="swatch-value">
                {{ trans('plugins/ecommerce::product-attribute-sets.color') }}
            </div>
            <div class="swatch-image">
                {{ trans('plugins/ecommerce::product-attribute-sets.image') }}
            </div>
            <div class="remove-item">{{ trans('plugins/ecommerce::product-attribute-sets.remove') }}</div>
        @endif
    </div>
    <ul class="swatches-list">
        @if (count($attributes) > 0)
            @foreach($attributes as $attribute)
                <li data-id="{{ $attribute->id }}" class="clearfix">
                    @if (!$isNotDefaultLanguage)
                        <div class="swatch-is-default">
                            <input type="radio" name="related_attribute_is_default" value="{{ $attribute->order }}" @if ($attribute->is_default) checked @endif>
                        </div>
                    @endif
                    <div class="swatch-title">
                        <input type="text" class="form-control" value="{{ $attribute->title }}">
                    </div>
                    @if (!$isNotDefaultLanguage)
                        <div class="swatch-slug">
                            <input type="text" class="form-control" value="{{ $attribute->slug }}">
                        </div>
                        <div class="swatch-value">
                            <input type="text" class="form-control input-color-picker" value="{{ $attribute->color }}">
                        </div>
                        <div class="swatch-image">
                            <div class="image-box-container">
                                @include('plugins/ecommerce::components.form.image', [
                                    'name' => '',
                                    'value' => $attribute->image,
                                    'thumb' => $attribute->image ? RvMedia::getImageUrl($attribute->image, 'thumb') : RvMedia::getDefaultImage(false),
                                ])
                            </div>
                        </div>
                        <div class="remove-item"><a href="#" class="font-red"><i class="fa fa-trash"></i></a></div>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
    @if (!$isNotDefaultLanguage)
        <button type="button" class="btn purple js-add-new-attribute">{{ trans('plugins/ecommerce::product-attribute-sets.add_new_attribute') }}</button>
    @endif
    <div class="clearfix"></div>
</div>
