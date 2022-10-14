<?php

namespace Botble\Menu\Http\Requests;

use Botble\Support\Http\Requests\Request;

class MenuNodeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.menu_id' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'data.menu_id' => trans('packages/menu::menu.menu_id'),
        ];
    }
}
