<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductVariationItemCacheDecorator extends CacheAbstractDecorator implements ProductVariationItemInterface
{
    /**
     * {@inheritDoc}
     */
    public function getVariationsInfo(array $versionIds)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getProductAttributes($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
