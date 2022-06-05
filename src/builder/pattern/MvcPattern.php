<?php

namespace mgine\builder\pattern;

use mgine\builder\Builder;
use \mgine\builder\generator\{
    ModelGenerator,
    ViewGenerator,
    ControllerGenerator
};

/**
 * MvcPattern
 *
 * @property ModelGenerator $model
 * @property ViewGenerator $view
 * @property ControllerGenerator $controller
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class MvcPattern extends Builder implements PatternInterface
{
    protected array $generators = [
        'model' => '\mgine\builder\generator\ModelGenerator',
        'view' => '\mgine\builder\generator\ViewGenerator',
        'controller' => '\mgine\builder\generator\ControllerGenerator'
    ];
}