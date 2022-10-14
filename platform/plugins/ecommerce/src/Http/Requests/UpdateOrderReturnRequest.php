<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Support\Http\Requests\Request;

class UpdateOrderReturnRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'return_status' => 'required|string|in:' . implode(',', OrderReturnStatusEnum::values()),
        ];
    }
}
