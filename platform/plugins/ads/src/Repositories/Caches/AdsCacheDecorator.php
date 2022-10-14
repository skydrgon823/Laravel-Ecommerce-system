<?php

namespace Botble\Ads\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Ads\Repositories\Interfaces\AdsInterface;

class AdsCacheDecorator extends CacheAbstractDecorator implements AdsInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
