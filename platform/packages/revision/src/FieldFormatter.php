<?php

namespace Botble\Revision;

class FieldFormatter
{
    /**
     * Format the value according to the provided formats.
     *
     * @param string $key
     * @param string|null $value
     * @param array $formats
     * @return string formatted value
     */
    public static function format(string $key, ?string $value, array $formats): ?string
    {
        foreach ($formats as $pkey => $format) {
            $parts = explode(':', $format);
            if (count($parts) === 1) {
                continue;
            }

            if ($pkey == $key) {
                $method = array_shift($parts);

                if (method_exists(get_class(), $method)) {
                    return self::$method($value, implode(':', $parts));
                }

                break;
            }
        }

        return $value;
    }
}
