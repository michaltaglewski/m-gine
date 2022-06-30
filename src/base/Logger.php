<?php

declare(strict_types=1);

namespace mgine\base;

use mgine\helpers\ClassHelper;

/**
 * Logger
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
abstract class Logger extends Component implements \mgine\log\LoggerInterface
{
    /**
     * @param string $type
     * @param string $message
     * @return void
     */
    abstract public function log(string $type, string $message): void;

    /**
     * @param string $name
     * @param array $arguments
     * @return bool|mixed
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        $const = strtoupper($name);
        $message = $arguments[0] ?? null;

        if(ClassHelper::classConstantExists($const, $this) && is_string($message)){
            $type = ClassHelper::getClassConstant($const, $this);

            $this->log($type, $message);

            return true;
        }

        return parent::__call($name, $arguments);
    }
}