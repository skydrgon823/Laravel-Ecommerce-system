<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Illuminate\Support\Collection;

if (!function_exists('get_featured_brands')) {
    /**
     * @param int $limit
     * @param array $with
     * @param array $withCount
     * @return mixed
     */
    function get_featured_brands(int $limit = 8, array $with = ['slugable'], array $withCount = [])
    {
        return app(BrandInterface::class)->advancedGet([
            'condition' => [
                'is_featured' => 1,
                'status'      => BaseStatusEnum::PUBLISHED,
            ],
            'order_by'  => [
                'order'      => 'ASC',
                'created_at' => 'DESC',
            ],
            'with'      => $with,
            'withCount' => $withCount,
            'take'      => $limit,
        ]);
    }
}

if (!function_exists('get_all_brands')) {
    /**
     * @param array $conditions
     * @param array $with
     * @param array $withCount
     * @return Collection
     */
    function get_all_brands(array $conditions = [], array $with = ['slugable'], array $withCount = []): Collection
    {
        return app(BrandInterface::class)->advancedGet([
            'condition' => $conditions,
            'order_by'  => [
                'order'      => 'ASC',
                'created_at' => 'DESC',
            ],
            'with'      => $with,
            'withCount' => $withCount,
        ]);
    }
}
