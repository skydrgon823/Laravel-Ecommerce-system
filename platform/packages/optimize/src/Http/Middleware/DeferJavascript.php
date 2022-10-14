<?php

namespace Botble\Optimize\Http\Middleware;

class DeferJavascript extends PageSpeed
{
    /**
     * @param $buffer
     * @return string
     */
    public function apply($buffer)
    {
        $replace = [
            '/<script(?=[^>]+src[^>]+)((?![^>]+defer|data-pagespeed-no-defer[^>]+)[^>]+)/i' => '<script $1 defer',
        ];

        return $this->replace($replace, $buffer);
    }
}
