<?php

namespace Botble\Location\Providers;

use Illuminate\Support\ServiceProvider;
use Kris\LaravelFormBuilder\FormHelper;
use Botble\Base\Forms\FormAbstract;
use Botble\Location\Fields\SelectLocationField;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter('form_custom_fields', function (FormAbstract $form, FormHelper $formHelper) {
            if (!$formHelper->hasCustomField('selectLocation')) {
                $form->addCustomField('selectLocation', SelectLocationField::class);
            }
            return $form;
        }, 29, 2);
    }
}
