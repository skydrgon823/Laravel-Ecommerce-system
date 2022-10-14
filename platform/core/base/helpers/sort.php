<?php

use Illuminate\Support\Collection;

if (!function_exists('sort_item_with_children')) {
    /**
     * Sort parents before children
     * @param Collection|array $list
     * @param array $result
     * @param int|null $parent
     * @param int $depth
     * @return array
     */
    function sort_item_with_children($list, array &$result = [], int $parent = null, int $depth = 0): array
    {
        if ($list instanceof Collection) {
            $listArr = [];
            foreach ($list as $item) {
                $listArr[] = $item;
            }

            $list = $listArr;
        }

        foreach ($list as $key => $object) {
            if ($object->parent_id == $object->id) {
                $result[] = $object;
                continue;
            }

            if ((int)$object->parent_id == (int)$parent) {
                $result[] = $object;
                $object->depth = $depth;
                unset($list[$key]);
                sort_item_with_children($list, $result, $object->id, $depth + 1);
            }
        }

        return $result;
    }
}
