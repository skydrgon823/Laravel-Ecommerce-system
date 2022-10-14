<?php

namespace Botble\Setting\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ResetEmailTemplateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'module'        => 'required|string|alpha_dash',
            'template_file' => 'required|string|alpha_dash',
        ];
    }
}
