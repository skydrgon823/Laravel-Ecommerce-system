<?php

namespace Botble\Shortcode\Compilers;

class Shortcode
{
    /**
     * Shortcode name
     *
     * @var string
     */
    protected $name;

    /**
     * Shortcode Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Shortcode content
     *
     * @var string
     */
    public $content;

    /**
     * Constructor
     *
     * @param string $name
     * @param array $attributes
     * @param string|null $content
     * @since 2.1
     */
    public function __construct(string $name, array $attributes = [], ?string $content = null)
    {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->content = $content;
    }

    /**
     * Get html attribute
     *
     * @param string $attribute
     * @param $fallback
     * @return string
     * @since 2.1
     */
    public function get(string $attribute, $fallback = null): string
    {
        $value = $this->{$attribute};

        if (!empty($value)) {
            return $attribute . '="' . $value . '"';
        } elseif (!empty($fallback)) {
            return $attribute . '="' . $fallback . '"';
        }

        return '';
    }

    /**
     * Get shortcode name
     *
     * @return string
     * @since 2.1
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get shortcode attributes
     *
     * @return string
     * @since 2.1
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Return array of attributes;
     *
     * @return array
     * @since 2.1
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Dynamically get attributes
     *
     * @param string $param
     * @return string|null
     * @since 2.1
     */
    public function __get(string $param)
    {
        return $this->attributes[$param] ?? null;
    }
}
