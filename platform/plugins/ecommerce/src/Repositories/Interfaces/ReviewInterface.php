<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface ReviewInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return mixed
     */
    public function getGroupedByProductId(array $productId);
}
