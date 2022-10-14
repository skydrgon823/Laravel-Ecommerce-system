<?php

use Botble\Widget\Models\Widget as WidgetModel;
use Illuminate\Database\Migrations\Migration;

class FixFooterWidget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        WidgetModel::create([
            'widget_id'  => 'PaymentMethodsWidget',
            'sidebar_id' => 'footer_sidebar',
            'position'   => 3,
            'data'       => [
                'id' => 'PaymentMethodsWidget',
            ],
            'theme'      => Theme::getThemeName(),
        ]);
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
}
