<li class="text-swatches-wrapper" data-type="text">
    <h6 class="widget-title" data-title="{{ $set->title }}">{{ $set->title }}</h6>
    <div class="attribute-values">
        <ul class="text-swatch">
            @foreach($attributes->where('attribute_set_id', $set->id) as $attribute)
                <li data-slug="{{ $attribute->slug }}">
                    <div class="custom-checkbox">
                        <label>
                            <input class="product-filter-item" type="checkbox" name="attributes[]" value="{{ $attribute->id }}" {{ in_array($attribute->id, $selected) ? 'checked' : '' }}>
                            <span>{{ $attribute->title }}</span>
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</li>
