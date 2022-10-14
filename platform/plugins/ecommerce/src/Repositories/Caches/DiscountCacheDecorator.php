<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class DiscountCacheDecorator extends CacheAbstractDecorator implements DiscountInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAvailablePromotions(array $with = [], bool $forProductSingle = false)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getProductPriceBasedOnPromotion(array $productIds = [], array $productCollections = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
