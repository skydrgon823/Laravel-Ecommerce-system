<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderProductCacheDecorator extends CacheAbstractDecorator implements OrderProductInterface
{
}
