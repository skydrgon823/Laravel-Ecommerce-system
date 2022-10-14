@if ($productAttributeSets->count() > 0)
    <div class="add-new-product-attribute-wrap">
        <input type="hidden" name="is_added_attributes" id="is_added_attributes" value="0">
        <a href="#" class="btn-trigger-add-attribute" data-bs-toggle-text="{{ trans('plugins/ecommerce::products.form.cancel') }}">{{ trans('plugins/ecommerce::products.form.add_new_attributes') }}</a>
        <p>{{ trans('plugins/ecommerce::products.form.add_new_attributes_description') }}</p>
        <div class="list-product-attribute-values-wrap hidden">
            <div class="product-select-attribute-item-template">
                <div class="product-attribute-set-item">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="text-title-field">{{ trans('plugins/ecommerce::products.form.attribute_name') }}</label>
                                <select class="next-input product-select-attribute-item">
                                    @foreach ($productAttributeSets as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="text-title-field">{{ trans('plugins/ecommerce::products.form.value') }}</label>
                                <div class="product-select-attribute-item-value-wrap">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 product-set-item-delete-action hidden">
                            <div class="form-group mb-3">
                                <label class="text-title-field">&nbsp;</label>
                                <div style="height: 36px;line-height: 33px;vertical-align: middle">
                                    <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @foreach ($productAttributeSets as $attributeSet)
                <div class="product-select-attribute-item-wrap-template product-select-attribute-item-value-wrap-{{ $attributeSet->id }}">
                    <select class="next-input product-select-attribute-item-value product-select-attribute-item-value-id-{{ $attributeSet->id }}" data-set-id="{{ $attributeSet->id }}">
                        @foreach ($attributeSet->attributes as $attribute)
                            <option value="{{ $attribute->id }}">
                                {{ $attribute->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
        <div class="list-product-attribute-wrap hidden">
            <div class="list-product-attribute-wrap-detail">
                <div class="product-attribute-set-item">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="text-title-field">{{ trans('plugins/ecommerce::products.form.attribute_name') }}</label>
                                <select class="next-input product-select-attribute-item">
                                    @foreach ($productAttributeSets as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="text-title-field">{{ trans('plugins/ecommerce::products.form.value') }}</label>
                                <div class="product-select-attribute-item-value-wrap">
                                    <select class="next-input product-select-attribute-item-value product-select-attribute-item-value-id-{{ $attributeSetId }}"  name="added_attributes[{{ $attributeSetId }}]" data-set-id="{{ $attributeSetId }}">
                                        @foreach ($productAttributes as $attribute)
                                            <option value="{{ $attribute->id }}">
                                                {{ $attribute->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 product-set-item-delete-action hidden">
                            <div class="form-group mb-3">
                                <label class="text-title-field">&nbsp;</label>
                                <div style="height: 36px;line-height: 33px;vertical-align: middle">
                                    <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-secondary btn-trigger-add-attribute-item @if ($productAttributeSets->count() < 2) hidden @endif">{{ trans('plugins/ecommerce::products.form.add_more_attribute') }}</a>
            @if ($product && is_object($product) && $product->id)
                <a href="#" class="btn btn-info btn-trigger-add-attribute-to-simple-product" data-target="{{ route('products.add-attribute-to-product', $product->id) }}">{{ trans('plugins/ecommerce::products.form.continue') }}</a>
            @endif
        </div>
    </div>

@else
    <p>{!! trans('plugins/ecommerce::products.form.create_product_variations', ['link' => Html::link(route('product-attribute-sets.create'), trans('plugins/ecommerce::products.form.add_new_attributes'))]) !!}</p>
@endif
