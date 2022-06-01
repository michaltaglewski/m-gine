<?php

namespace mgine\builder\pattern;

use mgine\builder\Pattern;
use \mgine\builder\generator\{
    ModelGenerator,
    ViewGenerator,
    ControllerGenerator
};

/**
 * @property ModelGenerator $model
 * @property ViewGenerator $view
 * @property ControllerGenerator $controller
 */

class MvcPattern extends Pattern
{
    public array $generators = [
        'model' => '\mgine\builder\generator\ModelGenerator',
        'view' => '\mgine\builder\generator\ViewGenerator',
        'controller' => '\mgine\builder\generator\ControllerGenerator'
    ];
}