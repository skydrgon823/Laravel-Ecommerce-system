<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Facades\DB;

class ProductAttributeSetRepository extends RepositoriesAbstract implements ProductAttributeSetInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByProductId($productId)
    {
        if (!is_array($productId)) {
            $productId = [$productId];
        }

        $data = $this->model
            ->join(
                'ec_product_with_attribute_set',
                'ec_product_attribute_sets.id',
                'ec_product_with_attribute_set.attribute_set_id'
            )
            ->whereIn('ec_product_with_attribute_set.product_id', $productId)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->distinct()
            ->with(['attributes'])
            ->select('ec_product_attribute_sets.*', 'ec_product_with_attribute_set.order')
            ->orderBy('ec_product_with_attribute_set.order', 'ASC');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllWithSelected($productId)
    {
        if (!is_array($productId)) {
            $productId = [esc_sql($productId)];
        }

        $data = $this->model
            ->leftJoin(DB::raw('
                (
                    SELECT ec_product_with_attribute_set.*
                    FROM ec_product_with_attribute_set
                    WHERE ec_product_with_attribute_set.product_id IN (' . implode(',', $productId) . ')
                ) AS PAR
            '), 'ec_product_attribute_sets.id', '=', 'PAR.attribute_set_id')
            ->distinct()
            ->select([
                'ec_product_attribute_sets.*',
                'PAR.product_id AS is_selected',
            ])
            ->with(['attributes'])
            ->orderBy('ec_product_attribute_sets.order', 'ASC')
            ->where('status', BaseStatusEnum::PUBLISHED);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
