<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderAddressCacheDecorator extends CacheAbstractDecorator implements OrderAddressInterface
{
}
