<?php

namespace Botble\Setting\Http\Requests;

use Botble\Support\Http\Requests\Request;

class LicenseSettingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_code'           => 'required',
            'buyer'                   => 'required|regex:/^[\pL\s\ \_\-0-9]+$/u',
            'license_rules_agreement' => 'accepted:1',
        ];
    }
}
