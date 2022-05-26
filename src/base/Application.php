<?php

namespace mgine\base;

use mgine\di\Container;

abstract class Application extends Component
{
    /**
     * @var string application's root directory
     */
    public string $basePath;

    /**
     * @var array framework application namespaces
     */
    public array $autoload = [];

    /**
     * @var string
     */
    public string $controllerNamespace = 'app/controllers';

    /**
     * @var string
     */
    public string $moduleNamespace = 'app/module';

    /**
     * @var Container Dependency Injection Container
     */
    public Container $container;

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        \App::$get = $this;

        $this->container = new Container();

        $this->configureAutoloader();
        $this->coreComponents();
    }

    /**
     * @return void
     */
    abstract public function run() :void;

    /**
     * @param string $route
     * @param array $params
     * @return mixed
     */
    abstract public function runAction(string $route, array $params = []): mixed;

    /**
     * @param Configurable $object
     * @param array $properties
     * @return void
     * @throws InvalidConfigException
     */
    public static function configure(Configurable $object, array $properties): void
    {
        if(isset($properties['class']) && $properties['class'] !== $object::class){
            throw new InvalidConfigException(sprintf('Configuration array class "%s" does not refer to the right Configurable object "%s".',
                $properties['class'], $object::class));
        }

        foreach ($properties as $name => $value){
            $object->$name = $value;
        }

        $object->config = $properties;
    }

    /**
     * Configures the autoloader namespace with existing paths
     * @return void
     * @throws InvalidConfigException
     */
    private function configureAutoloader(): void
    {
        if(empty($this->basePath)){
            throw new InvalidConfigException('Configuration $basePath attribute is requried.');
        }

        if(!is_dir($this->basePath)){
            throw new InvalidConfigException(sprintf('Configuration $basePath attribute "%s" is not a valid directory.', $this->basePath));
        }

        $this->autoload = [
            'app' => $this->basePath
        ];
    }

    /**
     * Method resolves $route path. Example 'index/index' route refers to actionIndex() in IndexController
     * @param string $route
     * @return array [$namespace, $controllerId, $actionId] complete data to locate the requested Controller action
     */
    protected function resolveControllerRoute(string $route): array
    {
        $namespace = $this->controllerNamespace;
        $parts = explode('/', $route);

        $i = count($parts);

        if($i < 2){
            throw new \Exception(sprintf('Unresolvable route "%s"', $route));
        }

        $actionId = strtolower(array_pop($parts));
        $controllerId = strtolower(array_pop($parts));
        $path = strtolower(implode('\\', $parts));

        if(!empty($path)){
            $namespace = sprintf('%s\%s\controllers', $this->moduleNamespace, $path);
        }

        return [
            $namespace, $controllerId, $actionId
        ];
    }

    /**
     * Final method creates new Controller instance
     * @param string $controllerId
     * @return \mgine\web\Controller | \mgine\base\BaseController
     * @throws \Exception
     */
    final protected function createController(string $controllerId)
    {
        $controllerName = $this->controllerNamespace . '\\' . ucfirst($controllerId) . 'Controller';

        if(!class_exists($controllerName)){
            throw new \Exception(sprintf('Controller class "%s" does not exist.', $controllerName));
        }

        return new $controllerName();
    }

    abstract protected function coreComponents() :void;

    /**
     * @param string $attributeName
     * @return false|void
     * @throws ContainerException
     * @throws InvalidConfigException
     */
    protected function initComponents(string $attributeName = 'components')
    {
        if(!isset($this->config[$attributeName]) || !is_array($this->config[$attributeName])){
            throw new InvalidConfigException(sprintf('Could not find required configuration array "%s"', $attributeName));
        }

        foreach ($this->config[$attributeName] as $name => $config){

            if(!array_key_exists('class', $config)) {
                throw new InvalidConfigException(sprintf('Component "%s" configuration does not contain required "class" attribute.', $name));
            }

            $class = $config['class'];
            $this->add($name, $class, $config);
        }
    }
}