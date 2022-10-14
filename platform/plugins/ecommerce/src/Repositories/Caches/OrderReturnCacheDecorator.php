<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderReturnCacheDecorator extends CacheAbstractDecorator implements OrderReturnInterface
{
}
