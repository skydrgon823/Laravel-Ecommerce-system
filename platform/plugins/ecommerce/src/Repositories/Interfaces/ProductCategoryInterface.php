<?php

namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface ProductCategoryInterface extends RepositoryInterface
{
    /**
     * get categories filter by $param.
     *
     * @param array $param
     * $param['active'] => [true,false]
     * $param['order_by'] => [ASC, DESC]
     * $param['is_child'] => [true,false, null]
     * $param['is_feature'] => [true,false, null]
     * $param['num'] => [int,null]
     * @return Collection categories model
     */
    public function getCategories(array $param);

    /**
     * @return mixed
     */
    public function getDataSiteMap();

    /**
     * @param int $limit
     */
    public function getFeaturedCategories($limit);

    /**
     * @param bool $active
     * @return mixed
     */
    public function getAllCategories($active = true);

    /**
     * @param array $conditions
     * @param array $with
     * @param array $withCount
     * @param bool $parentOnly
     * @return \Illuminate\Support\Collection
     */
    public function getProductCategories(
        array $conditions = [],
        array $with = [],
        array $withCount = [],
        bool $parentOnly = false
    );
}
