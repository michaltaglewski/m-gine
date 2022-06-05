<?php

namespace mgine\web;

use mgine\base\BaseView;
use mgine\helpers\HtmlHelper;

/**
 * View
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class View extends BaseView
{
    public string $title = 'Main Page';

    /**
     * @return string
     */
    public function lang(): string
    {
        return \App::$get->language;
    }

    /**
     * @return string
     */
    public function charset(): string
    {
        return \App::$get->response->charset;
    }

    /**
     * @return string
     */
    public function baseUrl(): string
    {
        return \App::$get->baseUrl;
    }

    /**
     * @return void
     */
    public function bodyEnd(): void
    {
        $content = implode('', $this->js);

        $request = \App::$get->request;

        $script = <<< JS
            jQuery( document ).ready(function() {
                {$content}
            });
            jQuery(document).ajaxSend(
                function(event, jqXHR) {
                     jqXHR.setRequestHeader("{$request->headers->csrfHeaderName}", $('meta[name="{$request->csrfMetaTokenAttr}"]').attr('content'));
                }
            );
        JS;

        echo HtmlHelper::tag('script', $script, ['type' => 'text/javascript']); // text/javascript
    }

    /**
     * @return void
     */
    public function beginJS()
    {
        ob_start();
    }

    /**
     * @return void
     */
    public function endJS()
    {
        $script = ob_get_clean();

        if(preg_match("/^\\s*\\<script\\>(.*)\\<\\/script\\>\\s*$/s", $script, $matches)){
            $script = $matches[1];
        }

        $this->registerJs($script);
    }

    /**
     * @param string $script
     * @return void
     */
    public function registerJs(string $script)
    {
        $this->js[] = $script;
    }
}