<?php

declare(strict_types=1);

/**
 * M-Gine main Application helper
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class App
{
    /**
     *
     * @var \mgine\web\Application | \mgine\base\Application
     */
    public static mgine\base\Application $get;

    public static function autoload($className)
    {
        foreach(static::$get->autoload as $namespace => $path){

            if(str_starts_with($className, $namespace)){
                $classFile = str_replace($namespace, $path, $className) . '.php';

                if(is_file($classFile)){
                    include $classFile;
                }
            }
        }
    }
}

spl_autoload_register(['App', 'autoload']);
