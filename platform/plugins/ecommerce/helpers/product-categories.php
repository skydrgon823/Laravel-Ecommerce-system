<?php

use Illuminate\Support\Collection;

if (!function_exists('get_product_categories')) {
    /**
     * @return Collection
     * @deprecated
     */
    function get_product_categories(): Collection
    {
        return ProductCategoryHelper::getAllProductCategories();
    }
}

if (!function_exists('get_product_categories_with_children')) {
    /**
     * @return array
     * @deprecated
     */
    function get_product_categories_with_children(): array
    {
        return ProductCategoryHelper::getAllProductCategoriesWithChildren();
    }
}
