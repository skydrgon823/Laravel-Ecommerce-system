<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Illuminate\Support\Collection;

class FlashSaleSupport
{
    /**
     * @var Collection
     */
    protected $flashSales = [];

    /**
     * @param Product $product
     * @return Product|null
     */
    public function flashSaleForProduct(Product $product): ?Product
    {
        if (!$this->flashSales) {
            $this->getAvailableFlashSales();
        }

        if (!$product->id) {
            return null;
        }

        foreach ($this->flashSales as $flashSale) {
            $productId = $product->id;
            if ($product->is_variation) {
                $productId = $product->original_product->id;
            }

            foreach ($flashSale->products as $flashSaleProduct) {
                if ($productId == $flashSaleProduct->id) {
                    return $flashSaleProduct;
                }
            }
        }

        return null;
    }

    /**
     * @return Collection
     */
    public function getAvailableFlashSales(): Collection
    {
        if (!$this->flashSales instanceof Collection) {
            $this->flashSales = collect([]);
        }

        if ($this->flashSales->count() == 0) {
            $this->flashSales = app(FlashSaleInterface::class)->getAvailableFlashSales(['products']);
        }

        return $this->flashSales;
    }
}
