<?php

namespace Botble\Ads\Supports;

use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Carbon\Carbon;
use Html;
use Illuminate\Support\Collection;
use RvMedia;

class AdsManager
{
    /**
     * @var Collection
     */
    protected $data = [];

    /**
     * Whether the settings data are loaded.
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * @var array
     */
    protected $locations = [];

    /**
     * AdsManager constructor.
     */
    public function __construct()
    {
        $this->locations = [
            'not_set' => trans('plugins/ads::ads.not_set'),
        ];
    }

    /**
     * @param string $location
     * @param array $attributes
     * @return string
     */
    public function display(string $location, array $attributes = []): string
    {
        $this->load();

        $data = $this->data
            ->where('location', $location)
            ->sortBy('order');

        if ($data->count() > 1) {
            $data = $data->random(1);
        }

        $html = '';
        foreach ($data as $item) {
            if (!$item->image) {
                continue;
            }

            $image = Html::image(RvMedia::getImageUrl($item->image), $item->name, ['style' => 'max-width: 100%'])
                ->toHtml();

            if ($item->url) {
                $image = Html::link(route('public.ads-click', $item->key), $image, ['target' => '_blank'], null, false)
                    ->toHtml();
            }

            $html .= Html::tag('div', $image, $attributes)->toHtml();
        }

        return $html;
    }

    /**
     * Make sure data is loaded.
     *
     * @param boolean $force Force a reload of data. Default false.
     * @return self
     */
    public function load(bool $force = false)
    {
        if (!$this->loaded || $force) {
            $this->data = $this->read();
            $this->loaded = true;
        }

        return $this;
    }

    /**
     * @return Collection
     */
    protected function read()
    {
        return app(AdsInterface::class)->getAll();
    }

    /**
     * @param string $location
     * @return bool
     */
    public function locationHasAds(string $location): bool
    {
        $this->load();

        return (bool)$this->data
            ->where('location', $location)
            ->sortBy('order')
            ->count();
    }

    /**
     * @param string|null $key
     * @param array $attributes
     * @param array $linkAttributes
     * @return string|null
     */
    public function displayAds(?string $key, array $attributes = [], array $linkAttributes = [])
    {
        if (!$key) {
            return null;
        }

        $this->load();

        $ads = $this->data
            ->where('key', $key)
            ->first();

        if (!$ads || !$ads->image) {
            return null;
        }

        $image = Html::image(RvMedia::getImageUrl($ads->image), $ads->name, ['style' => 'max-width: 100%'])->toHtml();

        if ($ads->url) {
            $image = Html::link(route('public.ads-click', $ads->key), $image, $linkAttributes + ['target' => '_blank'], null, false)
                ->toHtml();
        }

        return Html::tag('div', $image, $attributes)->toHtml();
    }

    /**
     * @param bool $isLoad
     * @param bool $isNotExpired
     * @return Collection
     */
    public function getData(bool $isLoad = false, bool $isNotExpired = false): Collection
    {
        if ($isLoad) {
            $this->load();
        }

        if ($isNotExpired) {
            return $this->data
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->filter(function ($item) {
                    if ($expiredAt = $item->getRawOriginal('expired_at')) {
                        return Carbon::parse($expiredAt)->gte(now());
                    }
                    return true;
                });
        }

        return $this->data;
    }

    /**
     * @param string $key
     * @param string $name
     * @return $this
     */
    public function registerLocation(string $key, string $name): self
    {
        $this->locations[$key] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getLocations(): array
    {
        return $this->locations;
    }
}
