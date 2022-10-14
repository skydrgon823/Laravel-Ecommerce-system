<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductVariationRepository;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;

class StoreAttributesOfProductService
{
    /**
     * @var ProductAttributeRepository
     */
    protected $productAttributeRepository;

    /**
     * @var ProductVariationRepository
     */
    protected $productVariationRepository;

    /**
     * StoreAttributesOfProductService constructor.
     * @param ProductAttributeInterface $productAttributeRepository
     * @param ProductVariationInterface $productVariationRepository =
     */
    public function __construct(
        ProductAttributeInterface $productAttributeRepository,
        ProductVariationInterface $productVariationRepository
    ) {
        $this->productAttributeRepository = $productAttributeRepository;

        $this->productVariationRepository = $productVariationRepository;
    }

    /**
     * @param Product $product
     * @param array $attributeSets
     * @param array $attributes
     * @return Product
     */
    public function execute(Product $product, array $attributeSets, array $attributes = []): Product
    {
        $product->productAttributeSets()->sync($attributeSets);

        if (!$attributes) {
            $attributes = $this->productAttributeRepository
                ->getModel()
                ->whereIn('attribute_set_id', $attributeSets)
                ->pluck('id')
                ->all();

            $attributes = $this->getSelectedAttributes($product, $attributes);
        }

        $this->productVariationRepository->correctVariationItems($product->id, $attributes);

        return $product;
    }

    /**
     * @param Product $product
     * @param array $attributes
     * @return array
     */
    protected function getSelectedAttributes(Product $product, array $attributes): array
    {
        $attributeSets = $product->productAttributeSets()
            ->select('attribute_set_id')
            ->pluck('attribute_set_id')
            ->toArray();

        $allRelatedAttributeBySet = $this->productAttributeRepository
            ->allBy([
                ['attribute_set_id', 'IN', $attributeSets],
            ], [], ['id'])
            ->pluck('id')
            ->toArray();

        $newAttributes = [];

        foreach ($attributes as $item) {
            if (in_array($item, $allRelatedAttributeBySet)) {
                $newAttributes[] = $item;
            }
        }

        return $newAttributes;
    }
}
