<?php

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

if (!function_exists('get_product_attribute_groups_for_product_list')) {
    /**
     * @param Collection $attributes
     * @return array
     */
    function get_product_attribute_groups_for_product_list(Collection $attributes): array
    {
        $groups = [];

        foreach ($attributes->groupBy('attribute_set_id') as $key => $item) {
            /**
             * @var Builder $item
             */
            $first = $item->first();
            $groups[] = [
                'attribute_set_id'                     => $key,
                'attribute_set_title'                  => $first->product_attribute_set_title,
                'product_attribute_set_slug'           => $first->product_attribute_set_slug,
                'product_attribute_set_order'          => $first->product_attribute_set_order,
                'product_attribute_set_display_layout' => $first->product_attribute_set_display_layout,
                'items'                                => $item,
            ];
        }

        return $groups;
    }
}
