<li class="visual-swatches-wrapper" data-type="visual">
    <h6 class="widget-title" data-title="{{ $set->title }}">{{ $set->title }}</h6>
    <div class="attribute-values">
        <ul class="visual-swatch">
            @foreach($attributes->where('attribute_set_id', $set->id) as $attribute)
                <li data-slug="{{ $attribute->slug }}"
                    data-bs-toggle="tooltip"
                    data-placement="top"
                    title="{{ $attribute->title }}">
                    <div class="custom-checkbox">
                        <label>
                            <input class="product-filter-item" type="checkbox" name="attributes[]" value="{{ $attribute->id }}" {{ in_array($attribute->id, $selected) ? 'checked' : '' }}>
                            <span style="{{ $attribute->getAttributeStyle() }}"></span>
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</li>
