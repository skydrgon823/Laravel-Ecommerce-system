<?php

namespace Botble\Ads\Facades;

use Botble\Ads\Supports\AdsManager;
use Illuminate\Support\Facades\Facade;

class AdsManagerFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AdsManager::class;
    }
}
