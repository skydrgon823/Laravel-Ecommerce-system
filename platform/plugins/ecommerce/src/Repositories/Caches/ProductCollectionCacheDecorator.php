<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductCollectionCacheDecorator extends CacheAbstractDecorator implements ProductCollectionInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
