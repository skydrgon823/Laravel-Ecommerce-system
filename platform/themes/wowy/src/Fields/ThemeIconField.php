<?php

namespace Theme\Wowy\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\FormField;
use Theme;

class ThemeIconField extends FormField
{
    /**
     * {@inheritDoc}
     */
    protected function getTemplate()
    {
        Assets::addScriptsDirectly(Theme::asset()->url('js/icons-field.js'))
            ->addStylesDirectly(Theme::asset()->url('css/vendors/fontawesome-all.min.css'))
            ->addStylesDirectly(Theme::asset()->url('css/vendors/wowy-font.css'));

        return Theme::getThemeNamespace() . '::partials.fields.icons-field';
    }
}
