<?php

namespace mgine\helpers;

use ReflectionClass;

/**
 * ClassHelper
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class ClassHelper
{
    /**
     * @param object|string $objectOrClass
     * @return string
     */
    public static function getClassDirectory(object|string $objectOrClass) :string
    {
        return dirname((new ReflectionClass($objectOrClass))->getFileName());
    }

    /**
     * @param object|string $objectOrClass
     * @return string
     */
    public static function getNamespacePath(object|string $objectOrClass): string
    {
        $reflector = new ReflectionClass($objectOrClass);
        $classDir = self::getClassBaseName($objectOrClass);

        return dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR . $classDir;
    }

    /**
     * @param object|string $objectOrClass
     * @return string
     */
    public static function getClassBaseName(object|string $objectOrClass) :string
    {
        return (new ReflectionClass($objectOrClass))->getShortName();
    }

    /**
     * @param object|string $objectOrClass
     * @return array
     */
    public static function classConstants(object|string $objectOrClass): array
    {
        $reflector = new ReflectionClass($objectOrClass);

        return array_diff($reflector->getConstants(), $reflector->getParentClass()->getConstants());
    }

    /**
     * @param string $name
     * @param object|string $objectOrClass
     * @return mixed
     */
    public static function getClassConstant(string $name, object|string $objectOrClass): mixed
    {
        $constants = self::classConstants($objectOrClass);

        return $constants[$name] ?? null;
    }

    /**
     * @param string $name
     * @param object|string $objectOrClass
     * @return bool
     */
    public static function classConstantExists(string $name, object|string $objectOrClass): bool
    {
        $constants = self::classConstants($objectOrClass);

        return array_key_exists($name, $constants);
    }
}