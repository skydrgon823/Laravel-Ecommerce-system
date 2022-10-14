<?php

namespace Botble\Support\Services\Cache;

interface CacheInterface
{
    /**
     * Retrieve data from cache.
     *
     * @param string $key
     * @return mixed PHP data result of cache
     */
    public function get($key);

    /**
     * Add data to the cache.
     *
     * @param string $key
     * @param $value
     * @param bool $minutes
     * @return mixed $value variable returned for convenience
     */
    public function put($key, $value, $minutes = false);

    /**
     * Test if item exists in cache
     * Only returns true if exists && is not expired.
     *
     * @param string $key
     * @return bool If cache item exists
     */
    public function has($key);

    /**
     * Flush cache
     *
     * @return bool If cache is flushed
     */
    public function flush();
}
