<?php

namespace mgine\di;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use Psr\Container\ContainerInterface;
use mgine\base\ContainerException;

/**
 * DI Container
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array Where instances are stored
     */
    public array $instance = [];

    /**
     * @param string $id
     * @return object|callable
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function get(string $id): object|callable
    {
        if(!$this->has($id)){
            $this->instance[$id] = $this->resolve($id);
        }

        return $this->instance[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->instance[$id]);
    }

    /**
     * @param string $id
     * @param object|callable $concrete
     * @return void
     */
    public function set(string $id, object|callable $concrete): void
    {
        $this->instance[$id] = $concrete;
    }

    /**
     * @param string $id
     * @return object
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function resolve(string $id): object
    {
        $reflectionClass = new ReflectionClass($id);

        if(!$reflectionClass->isInstantiable()){
            throw new ContainerException();
        }

        $constructor = $reflectionClass->getConstructor();

        if(!$constructor){
            return new $id;
        }

        $dependencies = $this->getMethodDependencies($id, $constructor);

        if(is_array($dependencies)){
            return $reflectionClass->newInstanceArgs($dependencies);
        }

        return new $id;
    }

    /**
     * @param string $id
     * @param ReflectionMethod $method
     * @return null|array
     * @throws ContainerException|ReflectionException
     */
    public function getMethodDependencies(string $id, ReflectionMethod $method): ?array
    {
        $parameters = $method->getParameters();

        if(!$parameters){
            return null;
        }

        return array_map(function (\ReflectionParameter $param) use ($id) {

            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new ContainerException(
                    sprintf('Failed to resolve class "%s" because param "%s" is missing a type hint.', $id, $name)
                );
            }

            if($type instanceof \ReflectionUnionType){
                throw new ContainerException(
                    sprintf('Failed to resolve class "%s" because of union type for param "%s".', $id, $name)
                );
            }

            if($type instanceof \ReflectionNamedType && !$type->isBuiltin()){
                return $this->get($type->getName());
            }

            throw new ContainerException(
                sprintf('Failed to resolve class "%s" because of invalid param "%s".', $id, $name)
            );

        }, $parameters);
    }
}