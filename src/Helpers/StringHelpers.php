<?php

namespace Rhaymison\ElephantChain\Helpers;

class StringHelpers
{
    /**
     * @param string $content
     * @return array|false|string[]
     */
    public static function clearString(string $content): array|false
    {
        $content = str_replace("\n", " ", $content);
        $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        $content = self::removeAccents($content);
        return preg_split('/\s+/', $content);
    }

    /**
     * @param string $text
     * @return string
     */
    public static function removeAccents(string $text): string
    {
        $text = preg_replace('/[áàâãäå]/ui', 'a', $text);
        $text = preg_replace('/[éèêë]/ui', 'e', $text);
        $text = preg_replace('/[íìîï]/ui', 'i', $text);
        $text = preg_replace('/[óòôõö]/ui', 'o', $text);
        $text = preg_replace('/[úùûü]/ui', 'u', $text);
        $text = preg_replace('/[ç]/ui', 'c', $text);
        return preg_replace('/[^a-z0-9\s]/ui', '', $text);
    }

    /**
     * @param $text
     * @return int
     */
    public static function getTokenCount($text): int
    {
        return count(preg_split('/\s+|(?<=\W)(?=\w)|(?<=\w)(?=\W)/', $text));
    }
}