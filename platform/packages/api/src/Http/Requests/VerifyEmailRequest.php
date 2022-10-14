<?php

namespace Botble\Api\Http\Requests;

use Botble\Support\Http\Requests\Request;

class VerifyEmailRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|string',
            'token' => 'required',
        ];
    }
}
