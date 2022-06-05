<?php

namespace mgine\builder;

use mgine\helpers\ClassHelper;

/**
 * Builder Generator
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Generator
{
    /**
     * @var string
     */
    public string $directory;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $extension = '.php';

    /**
     * @var string
     */
    public string $template = 'template';

    /**
     * @var string
     */
    public string $appNamespace = 'app';

    /**
     * @var string|null
     */
    public ?string $namespace = null;

    /**
     * @var string|null
     */
    public ?string $className = null;

    /**
     * @var string|null
     */
    public ?string $baseClass = null;

    /**
     * @var array|null
     */
    public ?array $classProperties;

    /**
     * @var array
     */
    public array $customParams = [];

    /**
     * @var string
     */
    private ?string $objectName = null;

    /**
     * @var string
     */
    private string $templatesPath;

    /**
     * @param $appNamespace
     */
    public function __construct($appNamespace = null)
    {
        if(is_string($appNamespace)){
            $this->appNamespace = $appNamespace;
        }

        $this->templatesPath = __DIR__ . '/generator/templates';


        if($this::class !== self::class){
            $classBaseName = ClassHelper::getClassBaseName($this);
            $this->objectName = strtolower(str_replace('Generator', '', $classBaseName));

            $this->setTemplate();
        }
    }

    /**
     * @return bool
     */
    public function isClass(): bool
    {
        return $this->className !== null;
    }

    /**
     * @param string $directory
     * @return void
     */
    public function setDirectory(string $directory): void
    {
        if(substr($directory, 0, 1) === '/'){
            $this->directory .= $directory;
        } else {
            $this->directory = $directory;
        }
    }

    /**
     * @param string|null $className
     * @param string|null $baseClass
     * @return void
     */
    public function setClassName(?string $className = null, ?string $baseClass = null): void
    {
        if($className !== null){
            $this->className = $className;
        }

        if($baseClass !== null){
            $this->baseClass = $baseClass;
        }
    }

    /**
     * @param array|null $classProperties
     * @return void
     */
    public function setClassProperties(?array $classProperties): void
    {
        $this->classProperties = $classProperties;
    }

    /**
     * @param array $params
     * @return void
     */
    public function setCustomParams(array $params): void
    {
        $this->customParams = $params;
    }

    /**
     * @param array $params
     * @return void
     */
    public function addCustomParams(array $params): void
    {
        $this->customParams = array_merge($this->customParams, $params);
    }

    /**
     * @return string
     */
    public function getClassNamespace(): string
    {
        return $this->appNamespace . '\\' . $this->namespace;
    }

    /**
     * @return string
     */
    public function getBasename(): string
    {
        if($this->isClass()){
            return $this->className . $this->extension;
        }

        return $this->name . $this->extension;
    }

    /**
     * @return string|null
     */
    public function getClassExtends(): ?string
    {
        if($this->baseClass !== null){
            return 'extends ' . '\\' . trim($this->baseClass, '\\');
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function getClassProperties(): ?array
    {
        if($this->isClass()){
            return $this->classProperties;
        }

        return null;
    }

    /**
     * @param FileDTO $config
     * @return void
     */
    public function configureFile(FileDTO $config): void
    {
        if($config->name !== null){
            $this->name = basename($config->name, $this->extension);
        }

        if($this->isClass()){
            $this->setClassName($config->className);
            $this->setClassProperties($config->classProperties);
        }

        if(is_string($config->directory) && !empty($config->directory)){
            $this->setDirectory($config->directory);
        }

        $this->addCustomParams($config->customParams);
        $this->setTemplate($config->template);
    }

    /**
     * @return File
     * @throws \Exception
     */
    public function createFile(): File
    {
        $this->validate();

        if(!is_file($this->template)) {
            throw new \Exception(sprintf('"%s" template file does not exist.', $this->template));
        }

        return new File($this->getBasename(), $this->getContent(), $this->directory);
    }

    /**
     * @return false|string
     */
    public function getContent(): string|false
    {
        ob_start();

        require $this->template;

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setTemplate(string $name = null): void
    {
        if($name === null){
            $name = $this->objectName;
        }

        $this->template = realpath($this->templatesPath . DIRECTORY_SEPARATOR . $name . $this->extension);
    }

    protected function validate()
    {
    }
}