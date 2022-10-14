<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\ProductCategoryHelper;
use Illuminate\Support\Facades\Facade;

class ProductCategoryHelperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ProductCategoryHelper::class;
    }
}
