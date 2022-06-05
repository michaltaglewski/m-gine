<?php

namespace mgine\helpers;

/**
 * InflectorHelper
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class InflectorHelper
{
    /**
     * Converts 'id-formatted-string' to 'camelCaseFormattedString';
     *
     * @param $string
     * @return string
     */
    public static function idToCamelCase($string): string
    {
        return preg_replace_callback('/[-_](.)/', function ($matches) {
            return strtoupper($matches[1]);
        }, $string);
    }
}