<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ProductCollectionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (request()->route()->getName()) {
            case 'product-collections.create':
                return [
                    'name' => 'required',
                    'slug' => 'required|unique:ec_product_collections',
                ];
            default:
                return [
                    'name' => 'required',
                ];
        }
    }
}
