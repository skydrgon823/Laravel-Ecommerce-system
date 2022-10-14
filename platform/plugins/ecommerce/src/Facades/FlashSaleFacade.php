<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\FlashSaleSupport;
use Illuminate\Support\Facades\Facade;

class FlashSaleFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FlashSaleSupport::class;
    }
}
