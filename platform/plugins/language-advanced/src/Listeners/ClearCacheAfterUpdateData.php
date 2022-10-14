<?php

namespace Botble\LanguageAdvanced\Listeners;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Support\Services\Cache\Cache;

class ClearCacheAfterUpdateData
{
    /**
     * Handle the event.
     *
     * @param UpdatedContentEvent $event
     * @return void
     */
    public function handle(UpdatedContentEvent $event)
    {
        if (setting('enable_cache', false)) {
            $cache = new Cache(app('cache'), get_class($event->data));
            $cache->flush();
        }
    }
}
