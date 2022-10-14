<?php

namespace Botble\Faq\Http\Requests;

use Botble\Support\Http\Requests\Request;

class FaqRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'question'    => 'required',
            'answer'      => 'required',
        ];
    }
}
