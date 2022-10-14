<?php

namespace Botble\Faq\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Exception;
use Illuminate\Support\Arr;
use MetaBox;

class CreatedContentListener
{
    /**
     * Handle the event.
     *
     * @param CreatedContentEvent $event
     * @return void
     */
    public function handle(CreatedContentEvent $event)
    {
        try {
            if ($event->request->has('content') && $event->request->has('faq_schema_config')) {
                $config = $event->request->input('faq_schema_config');
                if (!empty($config)) {
                    foreach ($config as $key => $item) {
                        if (!$item[0]['value'] && !$item[1]['value']) {
                            Arr::forget($config, $key);
                        }
                    }
                }

                if (empty($config)) {
                    MetaBox::deleteMetaData($event->data, 'faq_schema_config');
                } else {
                    MetaBox::saveMetaBoxData($event->data, 'faq_schema_config', $config);
                }
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
