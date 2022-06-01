<?php

namespace mgine\builder;

use mgine\base\InvalidCallException;

/**
 * Builder Pattern
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Pattern {

    /**
     * @var string
     */
    public string $namespace;

    /**
     * @var array
     */
    public array $files = [];

    /**
     * @var array
     */
    public array $generators = [];

    /**
     * @param array $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @param string $namespace
     * @return void
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @param array $files
     * @return void
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function generate(): array
    {
        $fileModels = [];

        foreach ($this->files as $config){

            $id = $config['id'];

            $file = $this->createFile($id, ($config['file'] ?? []));
            $path = $file->getPathFromCurrentRoot();

            $fileModels[$path] = $file;
        }

        return $fileModels;
    }

    /**
     * @param string $generatorId
     * @param array $config
     * @return File
     * @throws \Exception
     */
    private function createFile(string $generatorId, array $config = []): File
    {
        /** @var Generator $generator */

        $generator = $this->$generatorId;
        $name = $config['name'] ?? null;

        if(!empty($config['directory'])){
            $generator->setDirectory($config['directory']);
        }

        if(!empty($config['template'])){
            $generator->setTemplate($config['template']);
        }

        return $generator->createFile($name);
    }

    /**
     * @param $name
     * @return void
     * @throws InvalidCallException
     */
    public function __get($name)
    {
        if(isset($this->generators[$name])){
            $class = $this->generators[$name];

            if (!class_exists($class)) {
                throw new InvalidCallException(sprintf('Undefined Generator "%s".', $class));
            }

            $this->$name = new $class;

            return $this->$name;
        }
    }
}