<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class TaxRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'      => 'required|max:255',
            'percentage' => 'required|between:0,99.99',
            'priority'   => 'required|min:0',
        ];
    }
}
