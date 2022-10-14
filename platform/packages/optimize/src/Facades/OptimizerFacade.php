<?php

namespace Botble\Optimize\Facades;

use Botble\Optimize\Supports\Optimizer;
use Illuminate\Support\Facades\Facade;

class OptimizerFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Optimizer::class;
    }
}
