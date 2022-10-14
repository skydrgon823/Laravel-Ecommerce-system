<?php

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductTranslation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (is_plugin_active('language') &&
            is_plugin_active('language-advanced')
        ) {
            $records = [];
            foreach (Product::get() as $product) {
                foreach (Language::getActiveLanguage(['lang_code', 'lang_is_default']) as $language) {
                    if ($language->lang_is_default) {
                        continue;
                    }

                    $condition = [
                        'lang_code'      => $language->lang_code,
                        'ec_products_id' => $product->id,
                    ];

                    $existing = ProductTranslation::where($condition)->count();

                    if ($existing) {
                        continue;
                    }

                    $parentTranslation = ProductTranslation::where([
                        'lang_code'      => $language->lang_code,
                        'ec_products_id' => $product->original_product->id,
                    ])->first();

                    $data = [];
                    foreach (DB::getSchemaBuilder()->getColumnListing('ec_products_translations') as $column) {
                        if (!in_array($column, array_keys($condition))) {
                            $data[$column] = $parentTranslation ? $parentTranslation->{$column} : $product->original_product->{$column};
                        }
                    }

                    $data = array_merge($data, $condition);

                    $records[] = $data;
                }
            }

            ProductTranslation::insertOrIgnore($records);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
