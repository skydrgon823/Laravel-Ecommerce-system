<?php

namespace Botble\Optimize\Http\Middleware;

class RemoveComments extends PageSpeed
{
    /**
     * @param string $buffer
     * @return string
     */
    public function apply($buffer)
    {
        $replace = [
            '/<!--[^]><!\[](.*?)[^\]]-->/s' => '',
        ];

        return $this->replace($replace, $buffer);
    }
}
