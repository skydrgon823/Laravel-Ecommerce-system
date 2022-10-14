<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ProductCategoryRepository extends RepositoriesAbstract implements ProductCategoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCategories(array $param)
    {
        $param = array_merge([
            'active'      => true,
            'order_by'    => 'desc',
            'is_child'    => null,
            'is_featured' => null,
            'num'         => null,
        ], $param);

        $data = $this->model;

        if ($param['active']) {
            $data = $data->where('status', BaseStatusEnum::PUBLISHED);
        }

        if ($param['is_child'] !== null) {
            if ($param['is_child'] === true) {
                $data = $data->where('parent_id', '<>', 0);
            } else {
                $data = $data->whereIn('parent_id', [0, null]);
            }
        }

        if ($param['is_featured']) {
            $data = $data->where('is_featured', $param['is_featured']);
        }

        $data = $data->orderBy('order', $param['order_by']);

        if ($param['num'] !== null) {
            $data = $data->limit($param['num']);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getFeaturedCategories($limit)
    {
        $data = $this->model
            ->where([
                'status'      => BaseStatusEnum::PUBLISHED,
                'is_featured' => 1,
            ])
            ->select([
                'id',
                'name',
                'icon',
            ])
            ->with(['slugable'])
            ->orderBy('order')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllCategories($active = true)
    {
        $data = $this->model;
        if ($active) {
            $data = $data->where(['status' => BaseStatusEnum::PUBLISHED]);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getProductCategories(
        array $conditions = [],
        array $with = [],
        array $withCount = [],
        bool $parentOnly = false
    ) {
        $data = $this->model;

        if (!empty($conditions)) {
            $data = $data->where($conditions);
        }

        if (!empty($with)) {
            $data = $data->with($with);
        }

        if (!empty($withCount)) {
            $data = $data->withCount($withCount);
        }

        if ($parentOnly) {
            $data = $data->where(function ($query) {
                $query
                    ->whereNull('parent_id')
                    ->orWhere('parent_id', 0);
            });
        }

        $data = $data
            ->orderBy('order', 'ASC')
            ->orderBy('created_at', 'DESC');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
