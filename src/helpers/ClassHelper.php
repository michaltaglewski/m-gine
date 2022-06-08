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
}