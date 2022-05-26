<?php

namespace mgine\web;

use mgine\base\BaseView;
use mgine\helpers\HtmlHelper;

class View extends BaseView
{
    public $title = 'Main Page';

    public function charset()
    {
        return \App::$get->response->charset;
    }

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

    public function beginJS()
    {
        ob_start();
    }

    public function endJS()
    {
        $script = ob_get_clean();

        if(preg_match("/^\\s*\\<script\\>(.*)\\<\\/script\\>\\s*$/s", $script, $matches)){
            $script = $matches[1];
        }

        $this->registerJs($script);
    }

    public function registerJs(string $script)
    {
        $this->js[] = $script;
    }
}