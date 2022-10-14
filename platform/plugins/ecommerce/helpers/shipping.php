<?php

if (!function_exists('get_shipping_setting')) {
    /**
     * @param string $key
     * @param string|null $type
     * @param $default
     * @return array|string
     */
    function get_shipping_setting(string $key, ?string $type = null, $default = null)
    {
        $key = config('plugins.ecommerce.shipping.settings.prefix') . ($type ? $type . '_' : '') . $key;

        return setting($key, $default ?: config('plugins.ecommerce.shipping.' . $key));
    }
}
