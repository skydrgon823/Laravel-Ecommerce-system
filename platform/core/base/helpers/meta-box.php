<?php

use Illuminate\Database\Eloquent\Model;

if (!function_exists('add_meta_box')) {
    /**
     * @param string $id
     * @param string $title
     * @param callable $callback
     * @param string|null $screen
     * @param string $context
     * @param string $priority
     * @param null $callbackArgs
     * @deprecated since 5.7
     */
    function add_meta_box(
        string   $id,
        string   $title,
        callable $callback,
        ?string  $screen = null,
        string   $context = 'advanced',
        string   $priority = 'default',
        $callbackArgs = null
    ) {
        MetaBox::addMetaBox($id, $title, $callback, $screen, $context, $priority, $callbackArgs);
    }
}

if (!function_exists('get_meta_data')) {
    /**
     * @param Model|null|mixed $object
     * @param string $key
     * @param boolean $single
     * @param array $select
     * @return mixed
     * @deprecated since 5.7
     */
    function get_meta_data($object, string $key, bool $single = false, array $select = ['meta_value'])
    {
        return MetaBox::getMetaData($object, $key, $single, $select);
    }
}

if (!function_exists('get_meta')) {
    /**
     * @param Model|mixed $object
     * @param string $key
     * @param array $select
     * @return mixed
     * @deprecated since 5.7
     */
    function get_meta($object, string $key, array $select = ['meta_value'])
    {
        return MetaBox::getMeta($object, $key, $select);
    }
}

if (!function_exists('save_meta_data')) {
    /**
     * @param mixed $object
     * @param string $key
     * @param string $value
     * @param array|null $options
     * @return bool
     * @deprecated since 5.7
     */
    function save_meta_data($object, string $key, string $value, array $options = null): bool
    {
        return MetaBox::saveMetaBoxData($object, $key, $value, $options);
    }
}

if (!function_exists('delete_meta_data')) {
    /**
     * @param mixed $object
     * @param string $key
     * @return mixed
     * @deprecated since 5.7
     */
    function delete_meta_data($object, string $key)
    {
        return MetaBox::deleteMetaData($object, $key);
    }
}
