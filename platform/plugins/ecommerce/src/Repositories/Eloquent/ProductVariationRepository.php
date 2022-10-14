<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ProductVariationRepository extends RepositoriesAbstract implements ProductVariationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getVariationByAttributes($configurableProductId, array $attributes)
    {
        $allRelatedVariations = $this->model
            ->where('configurable_product_id', $configurableProductId)
            ->distinct()
            ->with('variationItems')
            ->get();

        $matchedVariation = $allRelatedVariations
            ->filter(function ($value) use ($attributes) {
                $items = $value->variationItems->pluck('attribute_id')->toArray();

                $items = array_unique($items);

                return array_equal(array_unique($attributes), $items);
            });

        return $this->applyBeforeExecuteQuery($matchedVariation, true)->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getVariationByAttributesOrCreate($configurableProductId, array $attributes)
    {
        $variation = $this->getVariationByAttributes($configurableProductId, $attributes);

        if (!$variation) {
            $variation = $this->create([
                'configurable_product_id' => $configurableProductId,
            ]);

            foreach ($attributes as $attribute) {
                ProductVariationItem::create([
                    'attribute_id' => $attribute,
                    'variation_id' => $variation->id,
                ]);
            }

            return [
                'variation' => $variation,
                'created'   => true,
            ];
        }

        return [
            'variation' => $variation,
            'created'   => false,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function correctVariationItems($configurableProductId, array $attributes)
    {
        if (!$attributes) {
            $attributes = [0];
        }

        $items = ProductVariationItem::join(
            'ec_product_variations',
            'ec_product_variations.id',
            '=',
            'ec_product_variation_items.variation_id'
        )
            ->whereRaw('ec_product_variation_items.id IN
                (
                    SELECT ec_product_variation_items.id
                    FROM ec_product_variation_items
                    JOIN ec_product_variations ON ec_product_variations.id = ec_product_variation_items.variation_id
                    WHERE ec_product_variations.configurable_product_id = ' . $configurableProductId . '
                    AND ec_product_variation_items.attribute_id NOT IN (' . implode(',', $attributes) . ')
                )
            ')
            ->where('ec_product_variations.configurable_product_id', $configurableProductId)
            ->distinct()
            ->pluck('ec_product_variation_items.id')
            ->all();

        return ProductVariationItem::whereIn('id', $items)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function getParentOfVariation($variationId, array $with = [])
    {
        $variation = $this->model
            ->where('product_id', $variationId);

        $variation = $variation->first();

        $this->resetModel();

        return empty($variation) ? Product::with($with)->find($variationId) : Product::with($with)
            ->find($variation->configurable_product_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributeIdsOfChildrenProduct($productId)
    {
        $result = $this->model
            ->join(
                'ec_product_variation_items',
                'ec_product_variation_items.variation_id',
                '=',
                'ec_product_variations.id'
            )
            ->distinct()
            ->select('ec_product_variation_items.attribute_id')
            ->where('ec_product_variations.product_id', $productId)
            ->get()
            ->pluck('attribute_id')
            ->toArray();

        $this->resetModel();

        return $result;
    }
}
