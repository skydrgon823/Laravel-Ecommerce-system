<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderReturnItemCacheDecorator extends CacheAbstractDecorator implements OrderReturnItemInterface
{
}
