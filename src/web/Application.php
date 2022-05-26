<?php

namespace mgine\web;

class Application extends \mgine\base\Application
{
    public string $controllerNamespace = 'app\controllers';

    public string $moduleNamespace = 'app\module';

    public Session $session;

    public Request $request;

    public Response $response;

    public UrlManager $urlManager;

    public Controller $controller;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->initComponents();
    }

    /**
     * @return void
     */
    public function run() :void
    {
        try {
            $this->response->content = $this->handleRequest();
            $this->response->send();

        } catch (HttpException $ex){
            $this->response->sendError($ex);
        }
    }

    public function createView(): mixed
    {
        return $this->container->get('mgine\web\View');
    }

    /**
     * @return string|null
     */
    protected function handleRequest(): mixed
    {
        list($route, $params) = $this->request->resolve();

        return $this->runAction($route, $params);
    }

    /**
     * @param string $route
     * @param array $params
     * @return string|null
     * @throws BadRequestHttpException
     */
    public function runAction(string $route, array $params = []): mixed
    {
        list(
            $this->controllerNamespace,
            $controllerId,
            $actionId
        ) = $this->resolveControllerRoute($route);

        $this->controller = $this->createController($controllerId);

        return $this->controller?->runAction($actionId, $params);
    }

    protected function coreComponents(): void
    {
        $this->add('urlManager', 'mgine\web\UrlManager');
        $this->add('errorHandler', 'mgine\web\errorHandler');

        $this->add('security', 'mgine\base\Security');
        $this->add('session', 'mgine\web\Session');

        $this->add('request', 'mgine\web\Request');
        $this->add('response', 'mgine\web\Response');
    }
}