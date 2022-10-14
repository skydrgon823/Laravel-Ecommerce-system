<?php

namespace Botble\Ecommerce\Services\Footprints;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TrackingLogger implements TrackingLoggerInterface
{
    /**
     * The Request instance.
     *
     * @var Request
     */
    protected $request;

    /**
     * Track the request.
     *
     * @param Request $request
     * @return Request
     */
    public function track(Request $request): Request
    {
        $this->request = $request;

        $data = $this->captureAttributionData();

        if ($data && !app(FootprinterInterface::class)->getFootprints()) {
            Cookie::queue(
                'botble_footprints_cookie_data',
                json_encode($data),
                604800,
                null,
                config('session.domain')
            );
        }

        return $this->request;
    }

    /**
     * @return array
     */
    protected function captureAttributionData(): array
    {
        $attributes = array_merge(
            [
                'footprint'         => $this->request->footprint(),
                'ip'                => $this->captureIp(),
                'landing_domain'    => $this->captureLandingDomain(),
                'landing_page'      => $this->captureLandingPage(),
                'landing_params'    => $this->captureLandingParams(),
                'referral'          => $this->captureReferral(),
                'gclid'             => $this->captureGCLID(),
                'fclid'             => $this->captureFCLID(),
            ],
            $this->captureUTM(),
            $this->captureReferrer(),
            $this->getCustomParameter()
        );

        return array_map(function (?string $item) {
            return is_string($item) ? substr($item, 0, 255) : $item;
        }, $attributes);
    }

    /**
     * @return array
     */
    protected function getCustomParameter(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    protected function captureIp(): ?string
    {
        return $this->request->ip();
    }

    /**
     * @return string
     */
    protected function captureLandingDomain(): string
    {
        return $this->request->server('SERVER_NAME');
    }

    /**
     * @return string
     */
    protected function captureLandingPage(): string
    {
        return $this->request->path();
    }

    /**
     * @return string|null
     */
    protected function captureLandingParams(): ?string
    {
        return $this->request->getQueryString();
    }

    /**
     * @return array
     */
    protected function captureUTM(): array
    {
        $parameters = ['utm_source', 'utm_campaign', 'utm_medium', 'utm_term', 'utm_content'];

        $utm = [];

        foreach ($parameters as $parameter) {
            if ($this->request->has($parameter)) {
                $utm[$parameter] = $this->request->input($parameter);
            } else {
                $utm[$parameter] = null;
            }
        }

        return $utm;
    }

    /**
     * @return array
     */
    protected function captureReferrer(): array
    {
        $referrer = [];

        $referrer['referrer_url'] = $this->request->headers->get('referer');

        $parsedUrl = parse_url($referrer['referrer_url']);

        $referrer['referrer_domain'] = $parsedUrl['host'] ?? null;

        return $referrer;
    }

    /**
     * @return string|null
     */
    protected function captureGCLID(): ?string
    {
        return $this->request->input('gclid');
    }

    /**
     * @return string|null
     */
    protected function captureFCLID(): ?string
    {
        return $this->request->input('fbclid');
    }

    /**
     * @return string|null
     */
    protected function captureReferral(): ?string
    {
        return $this->request->input('ref');
    }
}
