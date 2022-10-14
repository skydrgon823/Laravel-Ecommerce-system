<?php

namespace Botble\Ecommerce\Services\Footprints;

use Illuminate\Http\Request;

interface TrackingLoggerInterface
{
    /**
     * Track the request.
     *
     * @param Request $request
     * @return Request
     */
    public function track(Request $request): Request;
}
