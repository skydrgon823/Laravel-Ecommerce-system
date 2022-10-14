<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\DiscountSupport;
use Illuminate\Support\Facades\Facade;

class DiscountFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DiscountSupport::class;
    }
}
