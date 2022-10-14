<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductTranslation;
use Illuminate\Support\Facades\DB;
use Language;

class AddLanguageForVariantsListener
{
    /**
     * Handle the event.
     *
     * @param CreatedContentEvent | UpdatedContentEvent $event
     * @return void
     */
    public function handle($event)
    {
        if (is_plugin_active('language') &&
            is_plugin_active('language-advanced') &&
            get_class($event->data) == Product::class &&
            $event->data->is_variation == 0
        ) {
            $variations = $event->data->variations()->get();

            $records = [];
            foreach ($variations as $variation) {
                foreach (Language::getActiveLanguage(['lang_code', 'lang_is_default']) as $language) {
                    if ($language->lang_is_default) {
                        continue;
                    }

                    $condition = [
                        'lang_code'      => $language->lang_code,
                        'ec_products_id' => $variation->product->id,
                    ];

                    $existing = ProductTranslation::where($condition)->count();

                    if ($existing) {
                        continue;
                    }

                    $parentTranslation = ProductTranslation::where([
                        'lang_code'      => $language->lang_code,
                        'ec_products_id' => $event->data->id,
                    ])->first();

                    $data = [];
                    foreach (DB::getSchemaBuilder()->getColumnListing('ec_products_translations') as $column) {
                        if (!in_array($column, array_keys($condition))) {
                            $data[$column] = $parentTranslation ? $parentTranslation->{$column} : $event->data->{$column};
                        }
                    }

                    $data = array_merge($data, $condition);

                    $records[] = $data;
                }
            }

            ProductTranslation::insertOrIgnore($records);
        }
    }
}
