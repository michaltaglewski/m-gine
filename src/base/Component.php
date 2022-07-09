<?php

namespace mgine\base;

use ReflectionClass;

/**
 * Component
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
abstract class Component implements Configurable
{
    /**
     * @var array
     */
    public array $config = [];

    /**
     * @var string
     */
    public string $basePath;

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        if(!empty($config)){
            Application::configure($this, $config);
        }

        $this->basePath = \App::$basePath;

        $this->init();
    }

    /**
     * @return void
     */
    public function init()
    {
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws UnknownMethodException
     */
    public function __call(string $name, array $arguments)
    {
        throw new UnknownMethodException(sprintf('Trying to get unknown method "%s::%s"', $this::class, $name));
    }

    /**
     * @param $name
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __get($name)
    {
        /*if (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }*/

        throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * @param $class
     * @return bool
     */
    public function isConfigurable($class): bool
    {
        $reflectionClass = new ReflectionClass($class);

        if(!$reflectionClass->implementsInterface('mgine\base\Configurable')){
            return false;
        }

        if(!$reflectionClass->isInstantiable()){
            return false;
        }

        $constructor = $reflectionClass->getConstructor();

        if(!$constructor){
            return false;
        }

        $parameters = $constructor->getParameters();

        if(!$parameters){
            return false;
        }

        $param = reset($parameters);
        $name = $param->getName();

        if($param->isDefaultValueAvailable() && !is_array($param->getDefaultValue())){
            return false;
        }

        if($name === 'config'){
            return true;
        }

        return false;
    }
}