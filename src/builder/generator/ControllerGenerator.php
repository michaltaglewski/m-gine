<?php

namespace mgine\builder\generator;

class ControllerGenerator extends \mgine\builder\Generator
{
    public string $directory = 'controllers';

    public ?string $namespace = 'controllers';

    public ?string $className = 'IndexController';

    public ?string $baseClass = 'mgine\web\Controller';

    public function getActionIDs()
    {
        return [
            'index'
        ];
    }
}