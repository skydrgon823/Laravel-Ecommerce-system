<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class BrandCacheDecorator extends CacheAbstractDecorator implements BrandInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $condition = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
