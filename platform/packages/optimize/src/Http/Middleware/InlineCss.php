<?php

namespace Botble\Optimize\Http\Middleware;

class InlineCss extends PageSpeed
{
    /**
     * @var string
     */
    protected $html = '';

    /**
     * @var array
     */
    protected $class = [];

    /**
     * @var array
     */
    protected $style = [];

    /**
     * @var array
     */
    protected $inline = [];

    /**
     * @param string $buffer
     * @return string
     */
    public function apply($buffer)
    {
        $this->html = $buffer;

        preg_match_all(
            '#style="(.*?)"#',
            $this->html,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        $this->class = collect($matches[1])->mapWithKeys(function ($item) {
            return ['page_speed_' . rand() => $item[0]];
        })->unique();

        return $this->injectStyle()->injectClass()->fixHTML()->html;
    }

    /**
     * @return $this
     */
    protected function injectStyle(): InlineCss
    {
        collect($this->class)->each(function ($attributes, $class) {
            $this->inline[] = '.' . $class . '{' . $attributes . '}';

            $this->style[] = [
                'class'      => $class,
                'attributes' => preg_quote($attributes, '/'),
            ];
        });

        $injectStyle = implode(' ', $this->inline);

        $replace = [
            '#</head>(.*?)#' => "\n" . '<style>' . $injectStyle . '</style>' . "\n" . '</head>',
        ];

        $this->html = $this->replace($replace, $this->html);

        return $this;
    }

    /**
     * @return $this
     */
    protected function injectClass(): InlineCss
    {
        collect($this->style)->each(function ($item) {
            $replace = [
                '/style="' . $item['attributes'] . '"/' => 'class="' . $item['class'] . '"',
            ];

            $this->html = $this->replace($replace, $this->html);
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function fixHTML(): InlineCss
    {
        $newHTML = [];
        $tmp = explode('<', $this->html);

        $replaceClass = [
            '/class="(.*?)"/' => '',
        ];

        foreach ($tmp as $value) {
            preg_match_all('/class="(.*?)"/', $value, $matches);

            if (count($matches[1]) > 1) {
                $replace = [
                    '/>/' => 'class="' . implode(' ', $matches[1]) . '">',
                ];

                $newHTML[] = str_replace(
                    '  ',
                    ' ',
                    $this->replace($replace, $this->replace($replaceClass, $value))
                );
            } else {
                $newHTML[] = $value;
            }
        }

        $this->html = implode('<', $newHTML);

        return $this;
    }
}
