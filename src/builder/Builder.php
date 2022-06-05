<?php

namespace mgine\builder;

use mgine\base\{Component, InvalidConfigException, UnknownClassException};

/**
 * Builder
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Builder extends Component {

    /**
     * @var string
     */
    public string $rootPath;

    /**
     * @var string
     */
    public string $namespace = 'app';

    /**
     * @var array
     */
    private array $filesConfig = [];

    /**
     * @var array
     */
    protected array $generators = [
        'plain' => '\mgine\builder\Generator',
    ];

    /**
     * @param string $namespace
     * @return void
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * Adds a new file configuration.
     *
     *  $file['directory']
     *  A path that starts with '/' means a path relative
     *  to the default path of the given Generator type,
     *  otherwise it means a path relative to the application root directory.
     *
     *  The generator type 'view' refers to the relative path 'views', so:
     *  $file['directory'] '/index' refers to '/views/index'"
     *  $file['directory'] 'index' refers to '/index
     *
     * @TODO $generatorId = 'plain' should require $file['template'] to be set.
     *
     * @param string $generatorId
     * @param array $config
     * @return void
     * @throws InvalidConfigException
     */
    public function addFileConfig(string $generatorId, array $config = [])
    {
        $this->filesConfig[] = new FileDTO($generatorId, $config);
    }

    public function getFilesConfig(): array
    {
        return $this->filesConfig;
    }

    /**
     * @return array
     */
    public function generate(): array
    {
        $models = [];

        if(empty($this->filesConfig)){
            throw new InvalidConfigException('File configuration has not been entered. Nothing to generate.');
        }

        /**
         * @var FileDTO $config
         */

        foreach ($this->filesConfig as $config){

            /** @var Generator $generator */

            $id = $config->generatorId;

            $generator = $this->$id;

            $generator->configureFile($config);

            $file = $generator->createFile();

            unset($this->$id); // Once used has to be destroyed

            $path = $file->getPathFromCurrentRoot();

            $models[$path] = $file;
        }

        return $models;
    }

    /**
     * @param $name
     * @return void
     * @throws UnknownClassException
     */
    public function __get($name)
    {
        if(isset($this->generators[$name])){
            $class = $this->generators[$name];

            if (!class_exists($class)) {
                throw new UnknownClassException(sprintf('Undefined Generator "%s".', $class));
            }

            $this->$name = new $class;

            return $this->$name;
        }
    }
}