<?php

namespace Botble\Optimize\Http\Middleware;

class CollapseWhitespace extends PageSpeed
{
    /**
     * @param string $buffer
     * @return string
     */
    public function apply($buffer)
    {
        $replace = [
            "/\n([\S])/" => '$1',
            "/\r/"       => '',
            "/\n/"       => '',
            "/\t/"       => '',
            '/ +/'       => ' ',
            '/> +</'     => '><',
        ];

        return $this->replace($replace, $buffer);
    }
}
