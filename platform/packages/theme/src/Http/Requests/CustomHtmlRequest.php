<?php

namespace Botble\Theme\Http\Requests;

use Botble\Support\Http\Requests\Request;

class CustomHtmlRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'header_html' => 'max:2500',
            'body_html'   => 'max:2500',
            'footer_html' => 'max:2500',
        ];
    }
}
