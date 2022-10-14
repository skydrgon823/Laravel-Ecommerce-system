<?php

namespace Botble\Shortcode;

use Botble\Shortcode\Compilers\ShortcodeCompiler;
use Illuminate\Support\HtmlString;

class Shortcode
{
    /**
     * Shortcode compiler
     *
     * @var ShortcodeCompiler
     */
    protected $compiler;

    /**
     * Constructor
     *
     * @param ShortcodeCompiler $compiler
     * @since 2.1
     */
    public function __construct(ShortcodeCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Register a new shortcode
     *
     * @param string $key
     * @param string|null $name
     * @param string|null $description
     * @param null $callback
     * @param string $previewImage
     * @return Shortcode
     * @since 2.1
     */
    public function register(string $key, ?string $name, ?string $description = null, $callback = null, $previewImage = ''): Shortcode
    {
        $this->compiler->add($key, $name, $description, $callback, $previewImage);

        return $this;
    }

    /**
     * Enable the shortcode
     *
     * @return Shortcode
     * @since 2.1
     */
    public function enable(): Shortcode
    {
        $this->compiler->enable();

        return $this;
    }

    /**
     * Disable the shortcode
     *
     * @return Shortcode
     * @since 2.1
     */
    public function disable(): Shortcode
    {
        $this->compiler->disable();

        return $this;
    }

    /**
     * Compile the given string
     *
     * @param string $value
     * @param bool $force
     * @return HtmlString
     * @since 2.1
     */
    public function compile(string $value, bool $force = false): HtmlString
    {
        $html = $this->compiler->compile($value, $force);

        return new HtmlString($html);
    }

    /**
     * @param string|null $value
     * @return string|null
     * @since 2.1
     */
    public function strip(?string $value): ?string
    {
        return $this->compiler->strip($value);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->compiler->getRegistered();
    }

    /**
     * @param string $key
     * @param string|callable $html
     */
    public function setAdminConfig(string $key, $html)
    {
        $this->compiler->setAdminConfig($key, $html);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @return string
     */
    public function generateShortcode(string $name, array $attributes = []): string
    {
        $parsedAttributes = '';
        foreach ($attributes as $key => $attribute) {
            $parsedAttributes .= ' ' . $key . '="' . $attribute . '"';
        }

        return '[' . $name . $parsedAttributes . '][/' . $name . ']';
    }

    /**
     * @return ShortcodeCompiler
     */
    public function getCompiler(): ShortcodeCompiler
    {
        return $this->compiler;
    }
}
