<?php

namespace Botble\Optimize\Http\Middleware;

class RemoveQuotes extends PageSpeed
{
    public function apply($buffer)
    {
        $buffer = $this->replaceInsideHtmlTags($this->voidElements(), '/\/>/', '>', $buffer);

        $replace = [
            '/ src="(.\S*?)"/'         => ' src=$1',
            '/ width="(.\S*?)"/'       => ' width=$1',
            '/ height="(.\S*?)"/'      => ' height=$1',
            '/ name="(.\S*?)"/'        => ' name=$1',
            '/ charset="(.\S*?)"/'     => ' charset=$1',
            '/ align="(.\S*?)"/'       => ' align=$1',
            '/ border="(.\S*?)"/'      => ' border=$1',
            '/ crossorigin="(.\S*?)"/' => ' crossorigin=$1',
            '/ type="(.\S*?)"/'        => ' type=$1',
        ];

        return $this->replace($replace, $buffer);
    }

    /**
     * @return string[]
     */
    protected function voidElements(): array
    {
        return [
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr',
        ];
    }

    /**
     * Match all occurrences of the html tags given
     *
     * @param array $tags Html tags to match in the given buffer
     * @param string $buffer Middleware response buffer
     *
     * @return array $matches Html tags found in the buffer
     */
    protected function matchAllHtmlTag(array $tags, string $buffer): array
    {
        $voidTags = array_intersect($tags, $this->voidElements());
        $normalTags = array_diff($tags, $voidTags);

        return array_merge(
            $this->matchTags($voidTags, '/\<\s*(%tags)[^>]*\>/', $buffer),
            $this->matchTags($normalTags, '/\<\s*(%tags)[^>]*\>((.|\n)*?)\<\s*\/\s*(%tags)\>/', $buffer)
        );
    }

    /**
     * @param array $tags
     * @param string $pattern
     * @param string $buffer
     * @return array
     */
    protected function matchTags(array $tags, string $pattern, string $buffer): array
    {
        if (empty($tags)) {
            return [];
        }

        $normalizedPattern = str_replace('%tags', implode('|', $tags), $pattern);

        preg_match_all($normalizedPattern, $buffer, $matches);

        return $matches[0];
    }

    /**
     * Replace occurrences of regex pattern inside of given HTML tags
     *
     * @param array $tags Html tags to match and run regex to replace occurrences
     * @param string $regex Regex rule to match on the given HTML tags
     * @param string $replace Content to replace
     * @param string $buffer Middleware response buffer
     *
     * @return string $buffer Middleware response buffer
     */
    protected function replaceInsideHtmlTags(array $tags, string $regex, string $replace, string $buffer): string
    {
        foreach ($this->matchAllHtmlTag($tags, $buffer) as $tagMatched) {
            preg_match_all($regex, $tagMatched, $contentsMatched);

            $tagAfterReplace = str_replace($contentsMatched[0], $replace, $tagMatched);
            $buffer = str_replace($tagMatched, $tagAfterReplace, $buffer);
        }

        return $buffer;
    }
}
