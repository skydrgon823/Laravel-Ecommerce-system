<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class RefundRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'refund_amount' => 'numeric|min:0',
        ];

        foreach ($this->input('products', []) as $key => $item) {
            $rules[] = [
                'products.' . $key => 'numeric|min:0',
            ];
        }

        return $rules;
    }
}
