<?php

namespace Botble\Faq\Providers;

use Assets;
use BaseHelper;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use MetaBox;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws \Throwable
     */
    public function boot()
    {
        add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
            if (!$object || $context != 'advanced') {
                return false;
            }

            if (!in_array(get_class($object), config('plugins.faq.general.schema_supported', []))) {
                return false;
            }

            if (!setting('enable_faq_schema', 0)) {
                return false;
            }

            Assets::addStylesDirectly(['vendor/core/plugins/faq/css/faq.css'])
                ->addScriptsDirectly(['vendor/core/plugins/faq/js/faq.js']);

            MetaBox::addMetaBox(
                'faq_schema_config_wrapper',
                trans('plugins/faq::faq.faq_schema_config', [
                    'link' => Html::link(
                        'https://developers.google.com/search/docs/data-types/faqpage',
                        trans('plugins/faq::faq.learn_more'),
                        ['target' => '_blank']
                    ),
                ]),
                function () {
                    $value = [];

                    $args = func_get_args();
                    if ($args[0] && $args[0]->id) {
                        $value = MetaBox::getMetaData($args[0], 'faq_schema_config', true);
                    }

                    $hasValue = !empty($value);

                    $value = json_encode((array)$value);

                    return view('plugins/faq::schema-config-box', compact('value', 'hasValue'))->render();
                },
                get_class($object),
                $context
            );

            return true;
        }, 39, 2);

        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $object) {
            add_filter(THEME_FRONT_HEADER, function ($html) use ($object) {
                if (!in_array(get_class($object), config('plugins.faq.general.schema_supported', []))) {
                    return $html;
                }

                if (!setting('enable_faq_schema', 0)) {
                    return $html;
                }

                $value = MetaBox::getMetaData($object, 'faq_schema_config', true);

                if (!$value || !is_array($value)) {
                    return $html;
                }

                if (!empty($value)) {
                    foreach ($value as $key => $item) {
                        if (!$item[0]['value'] && !$item[1]['value']) {
                            Arr::forget($value, $key);
                        }
                    }
                }

                $schema = [
                    '@context'   => 'https://schema.org',
                    '@type'      => 'FAQPage',
                    'mainEntity' => [],
                ];

                foreach ($value as $item) {
                    $schema['mainEntity'][] = [
                        '@type'          => 'Question',
                        'name'           => BaseHelper::clean($item[0]['value']),
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => BaseHelper::clean($item[1]['value']),
                        ],
                    ];
                }

                $schema = json_encode($schema);

                return $html . Html::tag('script', $schema, ['type' => 'application/ld+json'])->toHtml();
            }, 39);
        }, 39, 2);

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 59);
    }

    /**
     * @param string|null $data
     * @return string
     */
    public function addSettings(?string $data = null): string
    {
        return $data . view('plugins/faq::settings')->render();
    }
}
