<?php

namespace Botble\Sitemap;

use Carbon\Carbon;
use DateTime;

class Model
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $sitemaps = [];

    /**
     * @var string
     */
    protected $title = null;

    /**
     * @var string
     */
    protected $link = null;

    /**
     * Enable or disable xsl styles.
     *
     * @var bool
     */
    protected $useStyles = true;

    /**
     * Set custom location for xsl styles (must end with slash).
     *
     * @var string
     */
    protected $sloc = '/vendor/core/packages/sitemap/styles/';

    /**
     * Enable or disable cache.
     *
     * @var bool
     */
    protected $useCache = false;

    /**
     * Unique cache key.
     *
     * @var string
     */
    protected $cacheKey = 'cms-sitemap.';

    /**
     * Cache duration, can be int or timestamp.
     *
     * @var Carbon|Datetime|int
     */
    protected $cacheDuration = 60;

    /**
     * Escaping html entities.
     *
     * @var bool
     */
    protected $escaping = true;

    /**
     * Use limitSize() for big sitemaps.
     *
     * @var bool
     */
    protected $useLimitSize = false;

    /**
     * Custom max size for limitSize().
     *
     * @var bool
     */
    protected $maxSize = null;

    /**
     * Use gzip compression.
     *
     * @var bool
     */
    protected $useGzip = false;

    /**
     * Populating model variables from configuration file.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->useCache = $config['use_cache'] ?? $this->useCache;
        $this->cacheKey = $config['cache_key'] ?? $this->cacheKey;
        $this->cacheDuration = $config['cache_duration'] ?? $this->cacheDuration;
        $this->escaping = $config['escaping'] ?? $this->escaping;
        $this->useLimitSize = $config['use_limit_size'] ?? $this->useLimitSize;
        $this->useStyles = $config['use_styles'] ?? $this->useStyles;
        $this->sloc = $config['styles_location'] ?? $this->sloc;
        $this->maxSize = $config['max_size'] ?? $this->maxSize;
        $this->useGzip = $config['use_gzip'] ?? $this->useGzip;
    }

    /**
     * Returns $items array.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Returns $sitemaps array.
     *
     * @return array
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    /**
     * Returns $title value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns $link value.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Returns $useStyles value.
     *
     * @return bool
     */
    public function isUseStyles()
    {
        return $this->useStyles;
    }

    /**
     * Returns $sloc value.
     *
     * @return string
     */
    public function getSloc()
    {
        return $this->sloc;
    }

    /**
     * Returns $useCache value.
     *
     * @return bool
     */
    public function isUseCache()
    {
        return $this->useCache;
    }

    /**
     * Returns $CacheKey value.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * Returns $CacheDuration value.
     *
     * @return string
     */
    public function getCacheDuration()
    {
        return $this->cacheDuration;
    }

    /**
     * Returns $escaping value.
     *
     * @return bool
     */
    public function isEscaping()
    {
        return $this->escaping;
    }

    /**
     * Returns $useLimitSize value.
     *
     * @return bool
     */
    public function isUseLimitSize()
    {
        return $this->useLimitSize;
    }

    /**
     * Returns $maxSize value.
     *
     * @return bool|mixed|null
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * Returns $useGzip value.
     *
     * @return bool|mixed
     */
    public function getUseGzip()
    {
        return $this->useGzip;
    }

    /**
     * Sets $escaping value.
     *
     * @param bool $escaping
     */
    public function setEscaping($escaping)
    {
        $this->escaping = $escaping;
    }

    /**
     * Adds item to $items array.
     *
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items[] = $items;
    }

    /**
     * Adds sitemap to $sitemaps array.
     *
     * @param array $sitemap
     */
    public function setSitemaps($sitemap)
    {
        $this->sitemaps[] = $sitemap;
    }

    /**
     * Sets $title value.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Sets $link value.
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Sets $useStyles value.
     *
     * @param bool $useStyles
     */
    public function setUseStyles($useStyles)
    {
        $this->useStyles = $useStyles;
    }

    /**
     * Sets $sloc value.
     *
     * @param string $sloc
     */
    public function setSloc($sloc)
    {
        $this->sloc = $sloc;
    }

    /**
     * Sets $useLimitSize value.
     *
     * @param bool $useLimitSize
     */
    public function setUseLimitSize($useLimitSize)
    {
        $this->useLimitSize = $useLimitSize;
    }

    /**
     * Sets $maxSize value.
     *
     * @param int $maxSize
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Sets $useGzip value.
     *
     * @param bool $useGzip
     */
    public function setUseGzip($useGzip = true)
    {
        $this->useGzip = $useGzip;
    }

    /**
     * Limit size of $items array to 50000 elements (1000 for google-news).
     */
    public function limitSize($max = 50000)
    {
        $this->items = array_slice($this->items, 0, $max);
    }

    /**
     * Reset $items array.
     *
     * @param array $items
     */
    public function resetItems($items = [])
    {
        $this->items = $items;
    }

    /**
     * Reset $sitemaps array.
     *
     * @param array $sitemaps
     */
    public function resetSitemaps($sitemaps = [])
    {
        $this->sitemaps = $sitemaps;
    }

    /**
     * Set use cache value.
     *
     * @param bool $useCache
     */
    public function setUseCache($useCache = true)
    {
        $this->useCache = $useCache;
    }

    /**
     * Set cache key value.
     *
     * @param string $cacheKey
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;
    }

    /**
     * Set cache duration value.
     *
     * @param Carbon|Datetime|int $cacheDuration
     */
    public function setCacheDuration($cacheDuration)
    {
        $this->cacheDuration = $cacheDuration;
    }
}
