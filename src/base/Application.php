<?php

namespace mgine\base;

use mgine\di\Container;

/**
 * Base Application
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
abstract class Application extends Component
{
    /**
     * Application's root directory.
     *
     * @var string
     */
    public string $basePath;

    /**
     * @var string
     */
    public string $language = 'en';

    /**
     * @var string
     */
    public string $charset = 'utf-8';

    /**
     * Application namespace @TODO
     *
     * @var string
     */
    public string $namespace = 'app';

    /**
     * @var array
     */
    public array $params = [];

    /**
     * Application Loader.
     *
     * @var loader
     */
    public Loader $loader;

    /**
     * @var string
     */
    public string $defaultRoute = 'index/index';

    /**
     * @var string
     */
    public string $defaultActionId = 'index';

    /**
     * @var string
     */
    public string $controllerNamespace = 'app/controllers';

    /**
     * @var string
     */
    public string $moduleNamespace = 'app/module';

    /**
     * @var Controller
     */
    public Controller $controller;

    /**
     * Dependency Injection Container.
     *
     * @var Container
     */
    public Container $container;

    /**
     * Application Constructor.
     *
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config)
    {
        \App::$get = $this;

        parent::__construct($config);

        $this->container = new Container();
        $this->loader = new Loader();

        $this->configureAutoloader();
        $this->coreComponents();
    }

    /**
     * The main method runs the entire application.
     *
     * @return void
     */
    abstract public function run() :void;

    /**
     * @param Request $request
     * @return array|string
     */
    abstract public function handleRequest(Request $request): array|string;

    /**
     * @param string $route
     * @param array $params
     * @return array|string
     */
    public function runAction($route, array $params = []): array|string
    {
        list(
            $this->controllerNamespace,
            $controllerId,
            $actionId
            ) = $this->resolveControllerRoute($route);

        $this->controller = $this->createController($controllerId);

        return $this->controller?->runAction($actionId, $params);
    }

    /**
     * Creates new Controller instance
     * @param string $controllerId
     * @return \mgine\web\Controller | \mgine\console\Controller
     * @throws \Exception
     */
    public function createController(string $controllerId)
    {
        $controllerName = $this->controllerNamespace . '\\' . ucfirst($controllerId) . 'Controller';

        if(!class_exists($controllerName)){
            throw new InvalidControllerException(sprintf('Controller class "%s" does not exist.', $controllerName));
        }

        return new $controllerName();
    }

    /**
     * @param $name
     * @return Component|null
     * @throws ContainerException
     * @throws InvalidCallException
     * @throws UnknownPropertyException
     * @throws \ReflectionException
     */
    public function __get($name)
    {
        if ($this->hasInstance($name)) {
            return $this->getInstance($name);
        }

        return parent::__get($name);
    }

    /**
     * Registers a new component to the Application
     *
     * @param string $name
     * @param string $class
     * @param array $config
     * @return false|void
     * @throws ContainerException
     * @throws UnknownClassException
     * @throws \ReflectionException
     */
    public function add(string $name, string $class, array $config = [])
    {
        if(!class_exists($class)){
            throw new UnknownClassException(sprintf('Class "%s" does not exist.', $class));
        }

        if($this->isConfigurable($class)){
            $instance = new $class($config);
        } else {
            try {
                $instance = $this->container->get($class);
            } catch (ContainerException) {
                throw new ContainerException(
                    sprintf('Failed to install component class "%s" because of unresolvable "__construct" dependencies.', $class)
                );
            }
        }

        if(empty($instance->config)){
            self::configure($instance, $config);
        }

        if(property_exists($this, $name)){
            $this->$name = $instance;
        } else {
            $this->addInstance($name, $instance);
        }
    }

    /**
     * Method resolves $route path. Example 'index/index' route refers to actionIndex() in IndexController
     *
     * @param $route
     * @return array
     * @throws EmptyRouteException
     * @throws InvalidRouteException
     */
    protected function resolveControllerRoute($route): array
    {
        if(!is_string($route) || empty($route)){
            throw new EmptyRouteException(sprintf('Route must not be empty.'));
        }

        if(!preg_match('/^[a-zA-Z_\x7f-\xff](?:[a-zA-Z0-9_\/\x7f-\xff]?)+(?<!\/)$/', $route)){
            throw new InvalidRouteException(sprintf('Route "%s" contains invalid characters.', $route));
        }

        if(strpos($route, '/') === false){
            $route .= '/' . $this->defaultActionId;
        }

        $parts = explode('/', $route);

        $namespace = $this->controllerNamespace;
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
     * @return void
     */
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

    /**
     * @param string $name
     * @param Component $instance
     * @return void
     * @throws InvalidConfigException
     */
    private function addInstance(string $name, Component $instance)
    {
        if ($this->hasInstance($name)) {
            throw new InvalidConfigException(sprintf('Component %s instance already created.', $name));
        }

        $this->container->set($name, $instance);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function hasInstance(string $name): bool
    {
        return $this->container->has($name);
    }

    /**
     * @param string $name
     * @return Component|null
     * @throws ContainerException
     * @throws \ReflectionException
     */
    private function getInstance(string $name): ?Component
    {
        $instance = $this->container->get($name);

        if(!$instance instanceof Component){
            throw new \Exception('1');
        }

        return $instance;
    }

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
     * @param $className
     * @return void
     */
    public static function autoload($className)
    {
        \App::$get->loader->autoload($className);
    }

    /**
     * Configures the autoloader namespace with existing paths.
     *
     * @return void
     * @throws InvalidConfigException
     */
    private function configureAutoloader(): void
    {
        if(empty($this->basePath)){
            throw new InvalidConfigException('Configuration $basePath attribute is required.');
        }

        if(!is_dir($this->basePath)){
            throw new InvalidConfigException(sprintf('Configuration $basePath attribute "%s" is not a valid directory.', $this->basePath));
        }

        $this->loader->registerNamespaces([
            $this->namespace => $this->basePath
        ]);

        $this->loader->register();
    }
}