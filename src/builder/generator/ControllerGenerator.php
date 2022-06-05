<?php

namespace mgine\builder\generator;

use mgine\base\InvalidConfigException;

/**
 *
 * ControllerGenerator
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class ControllerGenerator extends \mgine\builder\Generator
{
    public string $directory = 'controllers';

    public ?string $namespace = 'controllers';

    public ?string $className = 'IndexController';

    public ?string $baseClass = 'mgine\web\Controller';

    public array $customParams = [
        'actionIDs' => [
            'index'
        ]
    ];

    /**
     * @return array|string[]
     */
    public function getActionIDs(): array
    {
        return $this->customParams['actionIDs'];
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    protected function validate(): void
    {
        if(strpos($this->className, 'Controller') === false){
            throw new InvalidConfigException(sprintf('Invalid Controller className "%s".', $this->className));
        }
    }
}