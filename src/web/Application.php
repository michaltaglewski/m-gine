<?php

namespace mgine\web;

/**
 * Web Application
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Application extends \mgine\base\Application
{
    /**
     * Web Application's base URL.
     *
     * @var string
     */
    public string $baseUrl = '/';

    /**
     * @var string
     */
    public string $controllerNamespace = 'app\controllers';

    /**
     * @var string
     */
    public string $moduleNamespace = 'app\module';

    /**
     * @var Session
     */
    public Session $session;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var UrlManager
     */
    public UrlManager $urlManager;

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
            $this->response->content = $this->handleRequest($this->request);
            $this->response->send();

        } catch (HttpException $ex){
            $this->response->sendError($ex);
        }
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     * @throws \mgine\base\ContainerException
     */
    public function createView(): mixed
    {
        return $this->container->get('mgine\web\View');
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws NotFoundHttpException
     */
    public function handleRequest($request): array|string
    {
        list($route, $params) = $request->resolve();

        return $this->runAction($route, $params);
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @throws \mgine\base\ContainerException
     * @throws \mgine\base\InvalidConfigException
     */
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