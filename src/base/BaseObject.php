<?php

namespace mgine\base;

use mgine\helpers\InflectorHelper;
use ReflectionClass;

class BaseObject
{

//    public function __call(string $name, array $arguments)
//    {
//        throw new \Exception('Trying to call un');
//    }

//    public function __construct(array $config = [])
//    {
//        echo '<pre>';
//        var_dump($config);
//        echo '</pre>';
//
//        if(!empty($config)){
//            $this->configure($config);
//        }
//
//        $this->init();
//    }

    public function init(){}

    public function __set(string $name, $value): void
    {
        throw new \Exception(sprintf('Setting unknown variable %s in', $name));
    }

    protected function getNamespacePath()
    {
        $reflector = new ReflectionClass($this);
        $classDir = InflectorHelper::getClassBaseName($this);

        return dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR . $classDir;
    }

}