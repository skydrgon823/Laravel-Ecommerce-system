<div class="dropdown-swatches-wrapper attribute-swatches-wrapper" data-type="dropdown">
    <div class="attribute-name">{{ $set->title }}</div>
    <div class="attribute-values">
        <div class="dropdown-swatch">
            <label>
                <select class="form-control product-filter-item">
                    <option value="">{{ __('Select') . ' ' . strtolower($set->title) }}</option>
                    @foreach($attributes->where('attribute_set_id', $set->id) as $attribute)
                        <option
                                value="{{ $attribute->id }}"
                                data-id="{{ $attribute->id }}"
                                {{ $selected->where('id', $attribute->id)->count() ? 'selected' : '' }}
                                @if (!$variationInfo->where('id', $attribute->id)->count()) disabled="disabled" @endif>
                            {{ $attribute->title }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>
</div>
