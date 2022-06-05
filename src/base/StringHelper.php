<?php

namespace mgine\base;

/**
 * StringHelper
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class StringHelper
{
    /**
     * @param array $params
     * @param $separator
     * @return string
     */
    public static function parametrizeAssocArray(array $params, $separator = '='): string
    {
        $parts = array_map(function($k, $v) use ($separator) {
            return "$k{$separator}$v";
        }, array_keys($params), array_values($params));

        return implode(' ', $parts);
    }

    /**
     * Encodes "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     *
     * @param string $input encoded string.
     * @return string decoded string.
     */
    public static function base64UrlEncode($input)
    {
        return strtr(base64_encode($input), '+/', '-_');
    }

    /**
     * Decodes "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     *
     * @param string $input encoded string.
     * @return string decoded string.
     */
    public static function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}