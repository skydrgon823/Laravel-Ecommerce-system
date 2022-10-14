<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\OrderHelper;
use Illuminate\Support\Facades\Facade;

class OrderHelperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return OrderHelper::class;
    }
}
