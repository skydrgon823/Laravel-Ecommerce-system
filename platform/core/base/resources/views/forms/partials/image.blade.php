@php
    $allowThumb = Arr::get($attributes, 'allow_thumb', true);
    $defaultImage = Arr::get($attributes, 'default_image', RvMedia::getDefaultImage());
@endphp
<div class="image-box">
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="image-data">
    <div class="preview-image-wrapper @if (!$allowThumb) preview-image-wrapper-not-allow-thumb @endif">
        <img src="{{ RvMedia::getImageUrl($value, $allowThumb == true ? 'thumb' : null, false, $defaultImage) }}"
            data-default="{{ $defaultImage }}"
            alt="{{ trans('core/base::base.preview_image') }}"
            class="preview_image" @if ($allowThumb) width="150" @endif>
        <a class="btn_remove_image" title="{{ trans('core/base::forms.remove_image') }}">
            <i class="fa fa-times"></i>
        </a>
    </div>
    <div class="image-box-actions">
        <a href="#" class="btn_gallery" data-result="{{ $name }}"
            data-action="{{ $attributes['action'] ?? 'select-image' }}" data-allow-thumb="{{ $allowThumb == true }}">
            {{ trans('core/base::forms.choose_image') }}
        </a>
    </div>
</div>
