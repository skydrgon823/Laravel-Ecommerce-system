<?php

namespace Botble\Ecommerce\Services\Footprints;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Footprinter implements FootprinterInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $random;

    public function __construct()
    {
        $this->random = Str::random(20); // Will only be set once during requests since this class is a singleton
    }

    /**
     * @inheritDoc
     */
    public function footprint(Request $request): string
    {
        $this->request = $request;

        if ($request->hasCookie('botble_footprints_cookie')) {
            return $request->cookie('botble_footprints_cookie');
        }

        // This will add the cookie to the response
        Cookie::queue(
            'botble_footprints_cookie',
            $footprint = $this->fingerprint(),
            604800,
            null,
            config('session.domain')
        );

        return $footprint;
    }

    /**
     * This method will generate a fingerprint for the request based on the configuration.
     *
     * If relying on cookies then the logic of this function is not important, but if cookies are disabled this value
     * will be used to link previous requests with one another.
     *
     * @return string
     */
    protected function fingerprint(): string
    {
        // This is highly inspired from the $request->fingerprint() method
        return sha1(implode('|', array_filter([
            $this->request->ip(),
            $this->request->header('User-Agent'),
            $this->random,
        ])));
    }

    /**
     * @return array
     */
    public function getFootprints(): array
    {
        $data = Cookie::get('botble_footprints_cookie_data');

        if (!$data) {
            return [];
        }

        return json_decode($data, true);
    }
}
