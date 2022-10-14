<?php

namespace Botble\Optimize\Http\Middleware;

class TrimUrls extends PageSpeed
{
    /**
     * @param string $buffer
     * @return string
     */
    public function apply($buffer)
    {
        $replace = [
            '/https:/' => '',
            '/http:/'  => '',
        ];

        return $this->replace($replace, $buffer);
    }
}
