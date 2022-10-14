<?php

namespace Botble\Ecommerce\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Botble\Ecommerce\Services\Footprints\TrackingFilterInterface;
use Botble\Ecommerce\Services\Footprints\TrackingLoggerInterface;

class CaptureFootprintsMiddleware
{
    /**
     * @var TrackingFilterInterface
     */
    protected $filter;

    /**
     * @var TrackingLoggerInterface
     */
    protected $logger;

    public function __construct(TrackingFilterInterface $filter, TrackingLoggerInterface $logger)
    {
        $this->filter = $filter;
        $this->logger = $logger;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->filter->shouldTrack($request)) {
            $request = $this->logger->track($request);
        }

        return $next($request);
    }
}
