<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderCacheDecorator extends CacheAbstractDecorator implements OrderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRevenueData($startDate, $endDate, $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function countRevenueByDateRange($startDate, $endDate)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
