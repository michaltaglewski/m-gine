<?php

namespace mgine\web;

/**
 * Controller
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Controller extends \mgine\base\Controller
{
    public bool $csrfValidation = true;

    public string $layout = 'main';

    public function beforeAction()
    {
        if($this->csrfValidation){
            \App::$get->request->csrfValidate();
        }
    }
}