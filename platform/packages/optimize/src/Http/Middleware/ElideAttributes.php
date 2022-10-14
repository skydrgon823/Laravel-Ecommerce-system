<?php

namespace Botble\Optimize\Http\Middleware;

class ElideAttributes extends PageSpeed
{
    /**
     * @param string $buffer
     * @return string
     */
    public function apply($buffer)
    {
        $replace = [
            '/ method=("get"|get)/'   => '',
            '/ disabled=[^ >]*(.*?)/' => ' disabled',
            '/ selected=[^ >]*(.*?)/' => ' selected',
        ];

        return $this->replace($replace, $buffer);
    }
}
