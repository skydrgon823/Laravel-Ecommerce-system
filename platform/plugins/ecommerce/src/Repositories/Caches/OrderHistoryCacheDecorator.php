<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderHistoryCacheDecorator extends CacheAbstractDecorator implements OrderHistoryInterface
{
}
