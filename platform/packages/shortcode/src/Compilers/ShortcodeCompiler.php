<?php

namespace Botble\Shortcode\Compilers;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ShortcodeCompiler
{
    /**
     * Enabled state
     *
     * @var boolean
     */
    protected $enabled = false;

    /**
     * Enable strip state
     *
     * @var boolean
     */
    protected $strip = false;

    /**
     * @var mixed
     */
    protected $matches;

    /**
     * Registered shortcode
     *
     * @var array
     */
    protected $registered = [];

    /**
     * Enable
     *
     * @return $this
     * @since 2.1
     */
    public function enable(): self
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * Disable
     *
     * @return $this
     * @since 2.1
     */
    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * Add a new shortcode
     *
     * @param string $key
     * @param string|null $name
     * @param string|null $description
     * @param callable|string|null $callback
     * @param string $previewImage
     * @since 2.1
     */
    public function add(string $key, ?string $name, ?string $description = null, $callback = null, $previewImage = '')
    {
        $this->registered[$key] = compact('key', 'name', 'description', 'callback', 'previewImage');
    }

    /**
     * Compile the contents
     *
     * @param string $value
     * @param bool $force
     * @return string
     * @since 2.1
     */
    public function compile(string $value, bool $force = false): string
    {
        // Only continue is shortcode have been registered
        if ((!$this->enabled || !$this->hasShortcodes()) && !$force) {
            return $value;
        }

        // Set empty result
        $result = '';

        // Here we will loop through all the tokens returned by the Zend lexer and
        // parse each one into the corresponding valid PHP. We will then have this
        // template as the correctly rendered PHP that can be rendered natively.
        foreach (token_get_all($value) as $token) {
            $result .= is_array($token) ? $this->parseToken($token) : $token;
        }

        return $result;
    }

    /**
     * Check if shortcode have been registered
     *
     * @return bool
     * @since 2.1
     */
    public function hasShortcodes(): bool
    {
        return !empty($this->registered);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasShortcode(string $key): bool
    {
        return Arr::has($this->registered, $key);
    }

    /**
     * Parse the tokens from the template.
     *
     * @param array $token
     * @return string
     * @since 2.1
     */
    protected function parseToken(array $token): string
    {
        [$id, $content] = $token;
        if ($id == T_INLINE_HTML) {
            $content = $this->renderShortcodes($content);
        }

        return $content;
    }

    /**
     * Render shortcode
     *
     * @param string $value
     * @return string
     * @since 2.1
     */
    protected function renderShortcodes(string $value): string
    {
        $pattern = $this->getRegex();

        return preg_replace_callback('/' . $pattern . '/s', [$this, 'render'], $value);
    }

    /**
     * Render the current called shortcode.
     *
     * @param array $matches
     * @return string
     * @since 2.1
     */
    public function render(array $matches): ?string
    {
        // Compile the shortcode
        $compiled = $this->compileShortcode($matches);
        $name = $compiled->getName();

        // Render the shortcode through the callback
        return call_user_func_array($this->getCallback($name), [
            $compiled,
            $compiled->getContent(),
            $this,
            $name,
        ]);
    }

    /**
     * Get Compiled Attributes.
     *
     * @param $matches
     * @return Shortcode
     * @since 2.1
     */
    protected function compileShortcode($matches): Shortcode
    {
        // Set matches
        $this->setMatches($matches);
        // pars the attributes
        $attributes = $this->parseAttributes($this->matches[3]);

        // return shortcode instance
        return new Shortcode(
            $this->getName(),
            $attributes,
            $this->getContent()
        );
    }

    /**
     * Set the matches
     *
     * @param array $matches
     * @since 2.1
     */
    protected function setMatches(array $matches = [])
    {
        $this->matches = $matches;
    }

    /**
     * Return the shortcode name
     *
     * @return string
     * @since 2.1
     */
    public function getName(): ?string
    {
        return $this->matches[2];
    }

    /**
     * Return the shortcode content
     *
     * @return string
     * @since 2.1
     */
    public function getContent(): ?string
    {
        if (!$this->matches) {
            return null;
        }

        // Compile the content, to support nested shortcode
        return $this->compile($this->matches[5]);
    }

    /**
     * Get the callback for the current shortcode (class or callback)
     *
     * @param string $key
     * @return callable|array
     * @since 2.1
     */
    public function getCallback(string $key)
    {
        // Get the callback from the shortcode array
        $callback = $this->registered[$key]['callback'];
        // if is a string
        if (is_string($callback)) {
            // Parse the callback
            [$class, $method] = Str::parseCallback($callback, 'register');
            // If the class exist
            if (class_exists($class)) {
                // return class and method
                return [
                    app($class),
                    $method,
                ];
            }
        }

        return $callback;
    }

    /**
     * Parse the shortcode attributes
     * @param string|null $text
     * @return array
     * @since 2.1
     */
    protected function parseAttributes(?string $text): array
    {
        // decode attribute values
        $text = htmlspecialchars_decode($text, ENT_QUOTES);

        $attributes = [];
        // attributes pattern
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        // Match
        if (preg_match_all($pattern, preg_replace('/[\x{00a0}\x{200b}]+/u', ' ', $text), $match, PREG_SET_ORDER)) {
            foreach ($match as $item) {
                if (!empty($item[1])) {
                    $attributes[strtolower($item[1])] = stripcslashes($item[2]);
                } elseif (!empty($item[3])) {
                    $attributes[strtolower($item[3])] = stripcslashes($item[4]);
                } elseif (!empty($item[5])) {
                    $attributes[strtolower($item[5])] = stripcslashes($item[6]);
                } elseif (isset($item[7]) && strlen($item[7])) {
                    $attributes[] = stripcslashes($item[7]);
                } elseif (isset($item[8])) {
                    $attributes[] = stripcslashes($item[8]);
                }
            }
        } else {
            $attributes = ltrim($text);
        }

        // return attributes
        return is_array($attributes) ? $attributes : [$attributes];
    }

    /**
     * Get shortcode names
     *
     * @param array $except
     * @return string
     * @since 2.1
     */
    public function getShortcodeNames(array $except = []): string
    {
        $shortcodes = Arr::except($this->registered, $except);

        return join('|', array_map('preg_quote', array_keys($shortcodes)));
    }

    /**
     * Get shortcode regex.
     *
     * @param array $except
     * @return string
     * @since 2.1
     */
    protected function getRegex(array $except = []): string
    {
        $name = $this->getShortcodeNames($except);

        return '\\[(\\[?)(' . $name . ')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
    }

    /**
     * Remove all shortcode tags from the given content.
     *
     * @param string|null $content Content to remove shortcode tags.
     * @param array $except
     * @return string|null Content without shortcode tags.
     * @since 2.1
     */
    public function strip(?string $content, array $except = []): ?string
    {
        if (empty($this->registered)) {
            return $content;
        }

        $pattern = $this->getRegex($except);

        return preg_replace_callback('/' . $pattern . '/s', [$this, 'stripTag'], $content);
    }

    /**
     * @return bool
     * @since 2.1
     */
    public function getStrip(): bool
    {
        return $this->strip;
    }

    /**
     * @param bool $strip
     * @since 2.1
     */
    public function setStrip(bool $strip)
    {
        $this->strip = $strip;
    }

    /**
     * Remove shortcode tag
     *
     * @param string|array $match
     * @return string Content without shortcode tag.
     * @since 2.1
     */
    protected function stripTag($match): ?string
    {
        if ($match[1] == '[' && $match[6] == ']') {
            return substr($match[0], 1, -1);
        }

        return $match[1] . $match[6];
    }

    /**
     * @return array
     */
    public function getRegistered(): array
    {
        return $this->registered;
    }

    /**
     * @param string $key
     * @param string|callable|Closure $html
     */
    public function setAdminConfig(string $key, $html)
    {
        $this->registered[$key]['admin_config'] = $html;
    }

    /**
     * @param string $value
     * @return array|array[]
     */
    public function getAttributes(string $value): array
    {
        $pattern = $this->getRegex();

        preg_match('/' . $pattern . '/s', $value, $matches);

        if (!$matches) {
            return [];
        }

        // Set matches
        $this->setMatches($matches);

        // pars the attributes
        return $this->parseAttributes($this->matches[3]);
    }

    /**
     * @return string[]
     */
    public function whitelistShortcodes(): array
    {
        return apply_filters('core_whitelist_shortcodes', ['media', 'youtube-video']);
    }
}
