<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ShippingMethodRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     */
    public function rules()
    {
        $rules = [
            'name'  => 'required|max:120',
            'order' => 'required|integer|min:0',
        ];

        foreach (config(
            'plugins.ecommerce.shipping.integration_rules.' . $this->input('method_code'),
            []
        ) as $key => $rule) {
            $rules[$this->input('method_code') . '.' . $key] = $rule['rule'];
        }

        return $rules;
    }

    /**
     * @return array
     *
     */
    public function attributes()
    {
        $attributes = [];
        foreach (config(
            'plugins.ecommerce.shipping.integration_rules.' . $this->input('method_code'),
            []
        ) as $key => $rule) {
            $attributes[$this->input('method_code') . '.' . $key] = $rule['name'];
        }
        return $attributes;
    }
}
