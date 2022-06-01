<?php

namespace mgine\builder;

use mgine\helpers\ClassHelper;

/**
 * Builder Generator
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
abstract class Generator
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
     * @var string
     */
    private string $objectName;

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

        $classBaseName = ClassHelper::getClassBaseName($this);

        $this->objectName = strtolower(str_replace('Generator', '', $classBaseName));
        $this->templatesPath = ClassHelper::getClassDirectory($this) . '/templates';

        $this->setTemplate();
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
     * @param string|null $name
     * @return File
     * @throws \Exception
     */
    public function createFile(string $name = null): File
    {
        if(!is_file($this->template)) {
            throw new \Exception(sprintf('"%s" template file does not exist.', $this->template));
        }

        if($name !== null){
            $this->name = basename($name, $this->extension);
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
}