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
     * @param object $object
     * @return string
     */
    public static function getClassDirectory(object $object) :string
    {
        return dirname((new ReflectionClass($object))->getFileName());
    }

    /**
     * @param object $object
     * @return string
     */
    public static function getNamespacePath(object $object): string
    {
        $reflector = new ReflectionClass($object);
        $classDir = self::getClassBaseName($object);

        return dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR . $classDir;
    }

    /**
     * @param object $object
     * @return string
     */
    public static function getClassBaseName(object $object) :string
    {
        return (new ReflectionClass($object))->getShortName();
    }

    /**
     * @param object $object
     * @return array
     */
    public static function classConstants(object $object): array
    {
        $reflector = new \ReflectionClass($object);

        return array_diff($reflector->getConstants(), $reflector->getParentClass()->getConstants());
    }
}