<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Cart\Cart;
use Illuminate\Support\Facades\Facade;

class CartFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Cart::class;
    }
}
