<?php

namespace mgine\helpers;

class HtmlHelper extends \mgine\base\HtmlHelper
{

    private static array $closeTag = [
        'div', 'script'
    ];

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
     * @return string
     */
    private static function tagClose(string $string): string
    {
        return sprintf('</%s>', $string);
    }
}