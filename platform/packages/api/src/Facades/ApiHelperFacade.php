<?php

namespace Botble\Api\Facades;

use Botble\Api\Supports\ApiHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Botble\Api\Supports\ApiHelper
 */
class ApiHelperFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ApiHelper::class;
    }
}
