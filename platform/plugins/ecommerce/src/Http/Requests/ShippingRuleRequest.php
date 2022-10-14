<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ShippingRuleRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ruleItems = [];

        foreach ($this->input('shipping_rule_items', []) as $key => $item) {
            $ruleItems['shipping_rule_items.' . $key . '.adjustment_price'] = 'required|numeric';
        }

        return [
                'name'  => 'required|max:120',
                'from'  => 'required|numeric',
                'to'    => 'nullable|numeric|min:' . (float)$this->input('from'),
                'price' => 'required',
                'type'  => 'required',
            ] + $ruleItems;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attributes = [];
        foreach ($this->input('shipping_rule_items', []) as $key => $item) {
            $attributes['shipping_rule_items.' . $key . '.adjustment_price'] = trans(
                'plugins/ecommerce::shipping.adjustment_price_of',
                $key
            );
        }

        return $attributes;
    }
}
