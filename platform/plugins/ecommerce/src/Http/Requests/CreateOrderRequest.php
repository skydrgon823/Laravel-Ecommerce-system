<?php

namespace Botble\Ecommerce\Http\Requests;

use BaseHelper;
use Botble\Support\Http\Requests\Request;

class CreateOrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'            => 'required|exists:ec_customers,id',
            'customer_address.phone' => BaseHelper::getPhoneValidationRule(),
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'customer_id' => trans('plugins/ecommerce::order.customer_label'),
        ];
    }
}
