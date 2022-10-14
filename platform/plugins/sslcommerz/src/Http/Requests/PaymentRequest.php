<?php

namespace Botble\SslCommerz\Http\Requests;

use Botble\Support\Http\Requests\Request;

class PaymentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tran_id'  => 'required',
            'amount'   => 'required',
            'currency' => 'required',
            'value_a'  => 'required',
            'value_b'  => 'required',
        ];
    }
}
