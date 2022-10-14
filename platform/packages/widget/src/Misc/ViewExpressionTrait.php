<?php

namespace Botble\Widget\Misc;

use Illuminate\Support\HtmlString;

trait ViewExpressionTrait
{
    /**
     * Convert a given html to HtmlString object that was introduced in Laravel 5.1.
     *
     * @param string $html
     * @return HtmlString
     */
    protected function convertToViewExpression(string $html): HtmlString
    {
        return new HtmlString($html);
    }
}
