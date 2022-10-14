<?php

namespace Botble\Contact\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ContactRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'    => 'required',
            'email'   => 'required|email',
            'content' => 'required',
        ];

        if (is_plugin_active('captcha')) {
            if (setting('enable_captcha')) {
                $rules += [
                    'g-recaptcha-response' => 'required|captcha',
                ];
            }

            if (setting('enable_math_captcha_for_contact_form', 0)) {
                $rules['math-captcha'] = 'required|math_captcha';
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'                 => trans('plugins/contact::contact.form.name.required'),
            'email.required'                => trans('plugins/contact::contact.form.email.required'),
            'email.email'                   => trans('plugins/contact::contact.form.email.email'),
            'content.required'              => trans('plugins/contact::contact.form.content.required'),
            'g-recaptcha-response.required' => __('Captcha Verification Failed!'),
            'g-recaptcha-response.captcha'  => __('Captcha Verification Failed!'),
            'math-captcha.required'         => __('Math function Verification Failed!'),
            'math_captcha'                  => __('Math function Verification Failed!'),
        ];
    }
}
