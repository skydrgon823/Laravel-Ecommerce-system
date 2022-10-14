<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Eloquent\ProductRepository;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Exception;
use Throwable;

class RenderProductSwatchesSupport
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * RenderProductSwatchesSupport constructor.
     * @param ProductInterface $productRepository
     */
    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): RenderProductSwatchesSupport
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @param array $params
     * @return string
     * @throws Exception
     * @throws Throwable
     */
    public function render(array $params = []): string
    {
        $params = array_merge([
            'selected' => [],
            'view'     => 'plugins/ecommerce::themes.attributes.swatches-renderer',
        ], $params);

        $product = $this->product;

        $attributeSets = $product->productAttributeSets()->orderBy('order')->get();

        $attributes = $this->productRepository->getRelatedProductAttributes($this->product)->sortBy('order');

        $productVariations = app(ProductVariationInterface::class)->allBy([
            'configurable_product_id' => $product->id,
        ], ['product', 'productAttributes']);

        $productVariationsInfo = app(ProductVariationItemInterface::class)
            ->getVariationsInfo($productVariations->pluck('id')->toArray());

        $selected = $params['selected'];

        return view($params['view'], compact(
            'attributeSets',
            'attributes',
            'product',
            'selected',
            'productVariationsInfo',
            'productVariations'
        ))->render();
    }
}
