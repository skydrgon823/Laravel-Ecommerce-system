<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductVariationCacheDecorator extends CacheAbstractDecorator implements ProductVariationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getVariationByAttributes($configurableProductId, array $attributes)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getVariationByAttributesOrCreate($configurableProductId, array $attributes)
    {
        if ($this->getVariationByAttributes($configurableProductId, $attributes)) {
            return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
        }

        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function correctVariationItems($configurableProductId, array $attributes)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getParentOfVariation($variationId, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributeIdsOfChildrenProduct($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
