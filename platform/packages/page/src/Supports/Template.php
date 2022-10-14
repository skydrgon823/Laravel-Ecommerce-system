<?php

namespace Botble\Page\Supports;

use BaseHelper;
use Theme;

class Template
{
    /**
     * @param array $templates
     * @return void
     * @since 16-09-2016
     */
    public static function registerPageTemplate(array $templates = [])
    {
        $validTemplates = [];
        foreach ($templates as $key => $template) {
            if (in_array($key, self::getExistsTemplate())) {
                $validTemplates[$key] = $template;
            }
        }

        config([
            'packages.page.general.templates' => array_merge(
                config('packages.page.general.templates'),
                $validTemplates
            ),
        ]);
    }

    /**
     * @return array
     * @since 16-09-2016
     */
    protected static function getExistsTemplate(): array
    {
        $files = BaseHelper::scanFolder(theme_path(Theme::getThemeName() . DIRECTORY_SEPARATOR . config('packages.theme.general.containerDir.layout')));
        foreach ($files as $key => $file) {
            $files[$key] = str_replace('.blade.php', '', $file);
        }

        return $files;
    }

    /**
     * @return array
     * @since 16-09-2016
     */
    public static function getPageTemplates(): array
    {
        return (array)config('packages.page.general.templates', []);
    }
}
