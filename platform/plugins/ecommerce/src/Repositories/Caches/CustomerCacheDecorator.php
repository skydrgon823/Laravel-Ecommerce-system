<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class CustomerCacheDecorator extends CacheAbstractDecorator implements CustomerInterface
{
}
