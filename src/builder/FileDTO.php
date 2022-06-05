<?php

namespace mgine\builder;

use mgine\base\InvalidConfigException;

/**
 * File DataTransferObject
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class FileDTO
{
    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $directory;

    /**
     * @var string|null
     */
    public ?string $template;

    /**
     * @var string|null
     */
    public ?string $className;

    /**
     * @var array|null
     */
    public ?array $classProperties;

    /*
     * @var array
     */
    public array $customParams = [];

    /**
     * Constructor
     *
     * @param string $generatorId
     * @param $config
     * @throws InvalidConfigException
     */
    public function __construct(public string $generatorId, $config) {

        if(empty($generatorId)){
            throw new InvalidConfigException('$generatorId cannot be empty.');
        }

        $this->name ??= @!empty($config['name']) ? $config['name'] : null;
        $this->directory = @!empty($config['directory']) ? $config['directory'] : null;
        $this->template = @!empty($config['template']) ? $config['template'] : null;
        $this->className = @!empty($config['className']) ? $config['className'] : null;
        $this->classProperties = @!empty($config['classProperties']) ? $config['classProperties'] : null;

        $this->customParams = @!empty($config['customParams']) ? $config['customParams'] : [];
    }
}