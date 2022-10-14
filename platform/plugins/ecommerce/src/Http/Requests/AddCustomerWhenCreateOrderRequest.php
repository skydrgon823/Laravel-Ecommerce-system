<?php

namespace Botble\Ecommerce\Http\Requests;

use BaseHelper;
use Botble\Support\Http\Requests\Request;

class AddCustomerWhenCreateOrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'required|max:255',
            'email'      => 'required|max:60|min:6|email|unique:ec_customers',
            'phone'      => 'required|' . BaseHelper::getPhoneValidationRule(),
            'state'      => 'required|max:120',
            'city'       => 'required|max:120',
            'address'    => 'required|max:120',
            'is_default' => 'integer|min:0|max:1',
        ];
    }
}
