<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ReviewCacheDecorator extends CacheAbstractDecorator implements ReviewInterface
{
    /**
     * {@inheritDoc}
     */
    public function getGroupedByProductId($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
