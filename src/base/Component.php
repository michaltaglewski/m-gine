<?php

namespace mgine\base;

use mgine\di\Container;
use mgine\helpers\InflectorHelper;
use ReflectionClass;

abstract class Component implements Configurable
{
    /**
     * @var Container Dependency Injection Container
     */
    public Container $container;

    public array $config = [];

    public function __construct(array $config = [])
    {
        if(!empty($config)){
            Application:: configure($this, $config);
        }

        $this->init();
    }

    public function init()
    {
    }

    /**
     * @param string $name
     * @param $class
     * @param $config
     * @return false|void
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws \ReflectionException
     */
    public function add(string $name, $class, $config = [])
    {
        if(!class_exists($class)){
            return false;
        }

        if($this->isConfigurable($class)){
            $instance = new $class($config);
        } else {
            try {
                $instance = $this->container->get($class);
            } catch (ContainerException) {
                throw new ContainerException(
                    sprintf('Failed to install component class "%s" because of unresolvable "__construct" dependencies.', $class)
                );
            }
        }

        if(empty($instance->config)){
            Application::configure($instance, $config);
        }

        if(property_exists($this, $name)){
            $this->$name = $instance;
        } else {
            $this->addInstance($name, $instance);
        }
    }

    public function addInstance(string $name, Component $instance)
    {
        if ($this->hasInstance($name)) {
            throw new InvalidConfigException(sprintf('Component %s instance already created.', $name));
        }

        $this->container->set($name, $instance);
    }

    public function hasInstance(string $name): bool
    {
        return $this->container->has($name);
    }

    public function getInstance(string $name): ?Component
    {
        $instance = $this->container->get($name);

        if(!$instance instanceof Component){
            throw new Exception('1');
        }

        return $instance;
    }

    public function __call(string $name, array $arguments)
    {
        throw new \Exception('Trying to get unknown ' . $name);
    }

    public function __get($name)
    {
        if($this->hasInstance($name)){
            return $this->getInstance($name);
        }

        if (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    private function isConfigurable($class): bool
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

        if($param->hasType()){
            $name = $param->getName();
            $type = $param->getType();

            if($name === 'config' && $type->getName() === 'array'){
                return true;
            }
        }

        return false;
    }

    protected function getNamespacePath()
    {
        $reflector = new ReflectionClass($this);
        $classDir = InflectorHelper::getClassBaseName($this);

        return dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR . $classDir;
    }
}