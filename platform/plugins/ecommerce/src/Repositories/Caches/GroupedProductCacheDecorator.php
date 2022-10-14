<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class GroupedProductCacheDecorator extends CacheAbstractDecorator implements GroupedProductInterface
{
    /**
     * {@inheritDoc}
     */
    public function getChildren($groupedProductId, array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function createGroupedProducts($groupedProductId, array $childItems)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
