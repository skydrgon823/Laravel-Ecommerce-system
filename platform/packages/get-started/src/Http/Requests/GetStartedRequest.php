<?php

namespace Botble\GetStarted\Http\Requests;

use Botble\Support\Http\Requests\Request;

class GetStartedRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'step'                  => 'required|numeric',
            'username'              => 'required_if:step,3|max:30|min:4',
            'email'                 => 'required_if:step,3|max:60|min:6|email',
            'password'              => 'required_if:step,3|min:6|max:60',
            'password_confirmation' => 'required_if:step,3|same:password',
        ];
    }
}
