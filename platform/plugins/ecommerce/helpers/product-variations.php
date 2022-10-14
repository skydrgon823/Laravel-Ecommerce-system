<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Botble\Ecommerce\Supports\RenderProductAttributeSetsOnSearchPageSupport;
use Botble\Ecommerce\Supports\RenderProductSwatchesSupport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

if (!function_exists('render_product_swatches')) {
    /**
     * @param Product $product
     * @param array $params
     * @return string
     * @throws Throwable
     */
    function render_product_swatches(Product $product, array $params = []): string
    {
        Theme::asset()->container('footer')
            ->add('change-product-swatches', 'vendor/core/plugins/ecommerce/js/change-product-swatches.js', [
                'jquery',
            ]);

        $selected = [];

        $params = array_merge([
            'selected' => $selected,
            'view'     => 'plugins/ecommerce::themes.attributes.swatches-renderer',
        ], $params);

        $support = app(RenderProductSwatchesSupport::class);

        return $support->setProduct($product)->render($params);
    }
}

if (!function_exists('render_product_swatches_filter')) {
    /**
     * @param array $params
     * @return mixed
     * @throws Throwable
     */
    function render_product_swatches_filter(array $params = [])
    {
        return app(RenderProductAttributeSetsOnSearchPageSupport::class)->render($params);
    }
}

if (!function_exists('get_ecommerce_attribute_set')) {
    /**
     * @return LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    function get_ecommerce_attribute_set()
    {
        return app(ProductAttributeSetInterface::class)
            ->advancedGet([
                'condition' => [
                    'status'        => BaseStatusEnum::PUBLISHED,
                    'is_searchable' => 1,
                ],
                'order_by'  => [
                    'order' => 'ASC',
                ],
                'with'      => [
                    'attributes',
                ],
            ]);
    }
}

if (!function_exists('get_parent_product')) {
    /**
     * Helper get parent of product variation
     * @param int $variationId
     * @param array $with
     * @return Product|null
     */
    function get_parent_product(int $variationId, array $with = ['slugable']): ?Product
    {
        return app(ProductVariationInterface::class)->getParentOfVariation($variationId, $with);
    }
}

if (!function_exists('get_parent_product_id')) {
    /**
     * Helper get parent of product variation ID
     * @param int $variationId
     * @return int
     */
    function get_parent_product_id(int $variationId): ?int
    {
        $parent = get_parent_product($variationId);

        return $parent ? $parent->id : null;
    }
}

if (!function_exists('get_product_info')) {
    /**
     * @param int $variationId
     * @return Collection
     */
    function get_product_info(int $variationId): Collection
    {
        return app(ProductVariationItemInterface::class)->getVariationsInfo([$variationId]);
    }
}

if (!function_exists('get_product_attributes')) {
    /**
     * @param int $productId
     * @return Collection
     */
    function get_product_attributes(int $productId): Collection
    {
        return app(ProductVariationItemInterface::class)->getProductAttributes($productId);
    }
}
