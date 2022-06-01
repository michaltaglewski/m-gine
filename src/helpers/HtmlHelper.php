<?php

namespace mgine\helpers;

/**
 * HtmlHelper
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class HtmlHelper extends \mgine\base\HtmlHelper
{
    /**
     * @var array|string[]
     */
    private static array $closeTag = [
        'div', 'script'
    ];

    /**
     * @return string|null
     */
    public static function csrfMetaTags(): ?string
    {
        $request = \App::$get->request;
        $token = $request->getTrueCsrfToken();

        if($token === null){
            return null;
        }

        $tags[] = self::tag('meta', null, [
            'name' => $request->csrfMetaParamAttr,
            'content' => $request->csrfParamName,
        ]);

        $tags[] = self::tag('meta', null, [
            'name' => $request->csrfMetaTokenAttr,
            'content' => $request->getTrueCsrfToken(),
        ]);

        return implode("\n", $tags);
    }

    /**
     * @param string $name
     * @param $content
     * @param array $options
     * @return string
     */
    public static function tag(string $name, $content = null, array $options = []): string
    {
        $tagParams = array_merge([$name], ArrayHelper::parametrizeAssocArray($options));
        $tagOpen = self::tagOpen($tagParams);

        if(!in_array($name, self::$closeTag)){
            return $tagOpen;
        }

        return $tagOpen . $content . self::tagClose($name);
    }

    /**
     * @param string|array $tag
     * @return string
     */
    private static function tagOpen(string|array $tag): string
    {
        if(is_array($tag)){
            $tag = implode(' ', $tag);
        }

        return sprintf('<%s>', $tag);
    }

    /**
     * @param string $string
     * @return string
     */
    private static function tagClose(string $string): string
    {
        return sprintf('</%s>', $string);
    }
}