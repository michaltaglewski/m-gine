<?php

namespace mgine\console;

use mgine\base\{
    EmptyRouteException,
    InvalidControllerException
};
use mgine\helpers\ArrayHelper;

/**
 * Console Application
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Application extends \mgine\base\Application
{
    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var string
     */
    public string $defaultRoute = 'index/index';

    /**
     * @var array
     */
    public array $commandsList = [];

    /**
     * @param $config
     * @throws \mgine\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        try {
           $this->response->content = $this->handleRequest($this->request);
           $this->response->send();

        } catch (\Exception $ex){
            $this->response->sendError($ex);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function handleRequest($request): array|string
    {
        list($route, $params) = $request->resolve();

        return $this->runAction($route, $params);
    }

    /**
     * @param $route
     * @param array $params
     * @return mixed
     * @throws UnknownCommandException
     */
    public function runAction($route, array $params = []): array|string
    {
        try {
            return parent::runAction($route, $params);
        } catch(EmptyRouteException $ex) {
            return parent::runAction($this->defaultRoute);
        } catch(InvalidControllerException $ex) {
            throw new UnknownCommandException($route);
        }
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @throws \mgine\base\ContainerException
     * @throws \mgine\base\InvalidConfigException
     */
    protected function coreComponents() :void
    {
        $this->add('request', 'mgine\console\Request', [
            'routeAliases' => ArrayHelper::columnIndexed($this->commandsList, 'route')
        ]);

        $this->add('response', 'mgine\console\Response');
    }
}