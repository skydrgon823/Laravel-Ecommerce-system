<?php

use Botble\Theme\Facades\AdminBarFacade;
use Botble\Theme\Facades\ThemeOptionFacade;
use Botble\Theme\Supports\AdminBar as AdminBarBase;
use Illuminate\Contracts\Foundation\Application;

if (!function_exists('sanitize_html_class')) {
    /**
     * @param string $class
     * @param string|callable $fallback
     * @return string
     */
    function sanitize_html_class(string $class, $fallback = ''): string
    {
        //Strip out any % encoded octets
        $sanitized = preg_replace('|%[a-fA-F0-9][a-fA-F0-9]|', '', $class);

        //Limit to A-Z,a-z,0-9,_,-
        $sanitized = preg_replace('/[^A-Za-z0-9_-]/', '', $sanitized);

        if ('' == $sanitized && $fallback) {
            return sanitize_html_class($fallback);
        }

        /**
         * Filters a sanitized HTML class string.
         *
         * @param string $sanitized The sanitized HTML class.
         * @param string $class HTML class before sanitization.
         * @param string $fallback The fallback string.
         * @since 2.8.0
         */
        return apply_filters('sanitize_html_class', $sanitized, $class, $fallback);
    }
}

if (!function_exists('parse_args')) {
    /**
     * @param array|object $args
     * @param string|array $defaults
     * @return array
     */
    function parse_args($args, $defaults = '')
    {
        if (is_object($args)) {
            $result = get_object_vars($args);
        } else {
            $result =& $args;
        }

        if (is_array($defaults)) {
            return array_merge($defaults, $result);
        }

        return $result;
    }
}

if (!function_exists('theme')) {
    /**
     * Get the theme instance.
     *
     * @param string|null $themeName
     * @param string|null $layoutName
     * @return Application|mixed
     */
    function theme(?string $themeName = null, ?string $layoutName = null)
    {
        $theme = app('theme');

        if ($themeName) {
            $theme->theme($themeName);
        }

        if ($layoutName) {
            $theme->layout($layoutName);
        }

        return $theme;
    }
}

if (!function_exists('theme_option')) {
    /**
     * @return \Botble\Theme\ThemeOption|string
     */
    function theme_option($key = null, $default = '')
    {
        if (!empty($key)) {
            try {
                return ThemeOption::getOption($key, $default);
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }

        return ThemeOptionFacade::getFacadeRoot();
    }
}

if (!function_exists('theme_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function theme_path(?string $path = null): string
    {
        return platform_path('themes' . DIRECTORY_SEPARATOR . $path);
    }
}

if (!function_exists('admin_bar')) {
    /**
     * @return AdminBarBase
     */
    function admin_bar(): AdminBarBase
    {
        return AdminBarFacade::getFacadeRoot();
    }
}
