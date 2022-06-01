<?php

namespace mgine\builder;

use mgine\base\Component;

/**
 * ProjectBuilder
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class ProjectBuilder extends Component
{
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
    public array $files;

    /**
     * @var Pattern|null
     */
    public ?Pattern $pattern = null;

    /**
     * @param array $config
     * @throws \mgine\base\InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @param Pattern $pattern
     * @return void
     */
    public function setPattern(Pattern $pattern): void
    {
        $this->pattern = $pattern;
    }

    /**
     * @param $namespace
     * @return void
     */
    public function setNamespace($namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return array \mgine\builder\File[]
     */
    public function generate(): array
    {
        $files = [];

        if($this->pattern !== null){

            $this->pattern->setNamespace($this->namespace);

            $files = array_merge($files, $this->pattern->generate());
        }

        return $files;
    }
}