<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface DiscountInterface extends RepositoryInterface
{
    /**
     * @param array $with
     * @param bool $forProductSingle
     * @return Collection
     */
    public function getAvailablePromotions(array $with = [], bool $forProductSingle = false);

    /**
     * @param array $productIds
     * @param array $productCollectionIds
     * @return Eloquent[]|\Illuminate\Database\Eloquent\Collection|Model[]|Collection
     */
    public function getProductPriceBasedOnPromotion(array $productIds = [], array $productCollectionIds = []);
}
