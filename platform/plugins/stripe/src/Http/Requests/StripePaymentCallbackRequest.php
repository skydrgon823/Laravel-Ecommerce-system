<?php

namespace Botble\Stripe\Http\Requests;

use Botble\Support\Http\Requests\Request;

class StripePaymentCallbackRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'session_id' => 'required|min:66|max:66',
        ];
    }
}
