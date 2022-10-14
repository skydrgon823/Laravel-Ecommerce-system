<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface ProductAttributeSetInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return Collection
     */
    public function getByProductId($productId);

    /**
     * @param int $productId
     * @return Collection
     */
    public function getAllWithSelected($productId);
}
