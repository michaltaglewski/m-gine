<?php

namespace mgine\builder;

use mgine\builder\pattern\PatternInterface;

/**
 * ProjectBuilder component
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class ProjectBuilder extends Builder
{
    /**
     * @var PatternInterface|null
     */
    public ?PatternInterface $pattern = null;

    /**
     * Constructor
     *
     * @param array $config
     * @throws \mgine\base\InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Sets project Pattern [MvcPattern]
     *
     * @param PatternInterface $pattern
     * @param bool $inheritProjectNamespace
     * @return void
     */
    public function setPattern(PatternInterface $pattern, bool $inheritProjectNamespace = true): void
    {
        $this->pattern = $pattern;

        if($inheritProjectNamespace){
            $this->setNamespace($this->namespace);
        }
    }

    /**
     * Generates project files along with their content.
     *
     * @return array \mgine\builder\File[]
     */
    public function generate(): array
    {
        $files = parent::generate();

        if($this->pattern !== null){
            $files = array_merge($files, $this->pattern->generate());
        }

        return $files;
    }
}