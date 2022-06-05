<?php

namespace mgine\helpers;

/**
 * BaseArrayHelper
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class BaseArrayHelper
{
    /**
     * @param array $array
     * @return bool
     */
    public static function hasStringKeys(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * @param array $array
     * @param string|int|null $column
     * @return array
     */
    public static function columnIndexed(array $array, string|int|null $column): array
    {
        return array_filter(array_combine(array_keys($array), array_column($array, $column)));
    }

    /**
     * @param array $array
     * @return array
     */
    public static function toHttpHeaderFormat(array $array) :array
    {
        $line = [];

        foreach ($array as $name => $directives){

            $_directives = null;

            if(is_array($directives)){
                array_walk($directives, function (&$item, $key){
                    $item = is_numeric($key) ? trim($item) : implode('=', [trim($key), trim($item)]);
                });

                $_directives = implode('; ', $directives);

            } elseif(is_string($directives)) {
                $_directives = $directives;
            }

            $line[] = "$name: $_directives";
        }

        return $line;
    }

    /**
     * @param array $array
     * @param string $separator
     * @return array
     */
    public static function parametrizeAssocArray(array $array, string $separator = '='): array
    {
       return array_map(function($k, $v) use ($separator) {
            return sprintf('%s%s"%s"', $k, $separator, $v);
        }, array_keys($array), array_values($array));
    }

    /**
     * @param string $string
     * @param $separator
     * @param $paramsSeparator
     * @return array
     */
    public static function stringToParamsArray(string $string, $separator = '=', $paramsSeparator = ';'): array
    {
        $array = [];
        $params = explode($paramsSeparator, $string);

        foreach ($params as $item){

            $parts = explode($separator, $item);
            $attr = $parts[0];
            $val = $parts[1] ?? null;

            $array[$attr] = $val;
        }

        return $array;
    }

    /**
     * @param array $array
     * @param int $mode
     * @return void
     */
    public static function filterNumericKeys(array &$array, int $mode = ARRAY_FILTER_USE_KEY) :void
    {
        $array = array_filter(
            $array,
            fn ($key) => !is_numeric($key),
            $mode
        );
    }

}