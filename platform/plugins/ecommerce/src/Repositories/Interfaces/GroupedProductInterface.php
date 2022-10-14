<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface GroupedProductInterface extends RepositoryInterface
{
    /**
     * @param int $groupedProductId
     * @param array $params
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function getChildren($groupedProductId, array $params);

    /**
     * @param int $groupedProductId
     * @param array $childItems
     * @return bool
     */
    public function createGroupedProducts($groupedProductId, array $childItems);
}
