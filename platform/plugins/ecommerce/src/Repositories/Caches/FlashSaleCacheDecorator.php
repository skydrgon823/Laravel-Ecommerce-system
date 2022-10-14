<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class FlashSaleCacheDecorator extends CacheAbstractDecorator implements FlashSaleInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAvailableFlashSales(array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
