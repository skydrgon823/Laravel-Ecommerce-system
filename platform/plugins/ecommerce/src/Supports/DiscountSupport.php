<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Illuminate\Support\Collection;

class DiscountSupport
{
    /**
     * @var Collection
     */
    protected $promotions = [];

    /**
     * @param array $productIds
     * @param array $productCollectionIds
     * @return Discount|null
     */
    public function promotionForProduct(array $productIds, array $productCollectionIds): ?Discount
    {
        if (!$this->promotions) {
            $this->getAvailablePromotions();
        }

        foreach ($this->promotions as $promotion) {
            switch ($promotion->target) {
                case 'specific-product':
                case 'product-variant':
                    foreach ($promotion->products as $product) {
                        if (in_array($product->id, $productIds)) {
                            return $promotion;
                        }
                    }
                    break;

                case 'group-products':
                    foreach ($promotion->productCollections as $productCollection) {
                        if (in_array($productCollection->id, $productCollectionIds)) {
                            return $promotion;
                        }
                    }
                    break;

                case 'customer':
                    foreach ($promotion->customers as $customer) {
                        if ($customer->id == (auth('customer')->check() ? auth('customer')->id() : -1)) {
                            return $promotion;
                        }
                    }
                    break;
            }
        }

        return null;
    }

    /**
     * @return Collection
     */
    public function getAvailablePromotions(): Collection
    {
        if (!$this->promotions instanceof Collection) {
            $this->promotions = collect([]);
        }

        if ($this->promotions->count() == 0) {
            $this->promotions = app(DiscountInterface::class)
                ->getAvailablePromotions(['products', 'customers', 'productCollections'], true);
        }

        return $this->promotions;
    }
}
