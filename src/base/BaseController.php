<?php

namespace mgine\base;

use mgine\helpers\InflectorHelper;
use mgine\web\View;

abstract class BaseController extends Component
{
    public string $id;

    public string $actionId;

    public string $layout = 'main';

    protected string $controllerName;

    protected string $actionMethodName;

    protected array $actionRequiredParameters = [];

    protected array $actionOptionalParameters = [];

    protected array $actionArgs = [];

    private ?View $view = null;

    public function __construct()
    {
        $baseName = InflectorHelper::getClassBaseName($this);

        if(strpos($baseName, 'Controller') === false){
            throw new \Exception(sprintf('Invalid controller base name %s', $baseName));
        }

        $this->id = strtolower(str_replace('Controller', '', $baseName));
        $this->controllerName = get_class($this);

        parent::__construct();
    }

    public function beforeAction()
    {

    }

    public function setActionMethodName() :void
    {
        $this->actionMethodName = 'action' . ucfirst($this->actionId);
    }

    public function actionMethodExists() :bool
    {
        return method_exists($this, $this->actionMethodName);
    }

    public function setActionParametersArray() :void
    {
        $this->actionOptionalParameters = [];
        $this->actionRequiredParameters = [];

        $r = new \ReflectionMethod($this, $this->actionMethodName);

        foreach ($r->getParameters() as $param) {
            if($param->isOptional()){
                $this->actionOptionalParameters[$param->getName()] = $param->getType();
            } else {
                $this->actionRequiredParameters[$param->getName()] = $param->getType();
            }
        }
    }

    public function render(string $view, array $params = []) :string
    {
        if($this->view === null){
            $this->view = \App::$get->createView();
        }

        return $this->view->render($view, $params);
    }

    public function getViewPath()
    {
        return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->id;
    }

    public function getLayoutFile()
    {
        return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR .  'layouts' . DIRECTORY_SEPARATOR . $this->layout;
    }

    final protected function setActionArgs(array $args) :void
    {
        $this->actionArgs = $args;
    }

    final protected function filterArgs()
    {
        $paramsAll = array_merge($this->actionRequiredParameters, $this->actionOptionalParameters);

        $this->actionArgs = array_intersect_key($this->actionArgs, $paramsAll);
    }

    final protected function getActionMissingParameters()
    {
        return array_values(array_diff(array_keys($this->actionRequiredParameters), array_keys($this->actionArgs)));
    }
}