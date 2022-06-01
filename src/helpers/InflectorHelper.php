<?php

namespace mgine\helpers;

/**
 * InflectorHelper
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class InflectorHelper
{
    /**
     * @param $str
     * @param array $noStrip
     * @return string
     */
    public static function camelCase($str, array $noStrip = []): string
    {
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);

        $str = trim($str);

        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }
}