<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\OrderReturnHelper;
use Illuminate\Support\Facades\Facade;

class OrderReturnHelperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return OrderReturnHelper::class;
    }
}
