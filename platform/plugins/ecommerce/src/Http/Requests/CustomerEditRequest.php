<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class CustomerEditRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'  => 'required|max:120|min:2',
            'email' => 'required|max:60|min:6|email|unique:ec_customers,email,' . $this->route('customer'),
        ];

        if ($this->input('is_change_password') == 1) {
            $rules['password'] = 'required|min:6';
            $rules['password_confirmation'] = 'required|same:password';
        }

        return $rules;
    }
}
