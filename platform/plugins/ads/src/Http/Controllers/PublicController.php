<?php

namespace Botble\Ads\Http\Controllers;

use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;

class PublicController extends BaseController
{
    /**
     * @var AdsInterface
     */
    protected $adsRepository;

    /**
     * @param AdsInterface $adsRepository
     */
    public function __construct(AdsInterface $adsRepository)
    {
        $this->adsRepository = $adsRepository;
    }

    /**
     * @param string $key
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getAdsClick($key, BaseHttpResponse $response)
    {
        $ads = $this->adsRepository->getFirstBy(compact('key'));

        if (!$ads || !$ads->url) {
            return $response->setNextUrl(route('public.single'));
        }

        $ads->clicked++;
        $ads->save();

        return $response->setNextUrl($ads->url);
    }
}
