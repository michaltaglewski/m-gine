<?php

namespace mgine\base;

/**
 * Loader
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Loader extends Component
{
    public array $namespaces = [];

    /**
     * @param array $namespaces
     * @return void
     */
    public function registerNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
    }

    /**
     * @param $className
     * @return void
     */
    public function autoload($className): void
    {
        foreach($this->namespaces as $namespace => $path){

            if(str_starts_with($className, $namespace)){
                $classFile = str_replace($namespace, $path, $className) . '.php';

                if(is_file($classFile)){
                    include $classFile;
                }
            }
        }
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'autoload']);
    }
}