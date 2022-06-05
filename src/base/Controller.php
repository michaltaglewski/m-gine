<?php

namespace mgine\base;

use mgine\web\View;
use mgine\helpers\ClassHelper;

/**
 * Base Controller Class
 * MVC pattern Controller component.
 *
 * Extend this class in any new controllers:
 *     class IndexController extends \mgine\base\Controller
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
abstract class Controller extends Component
{
    /**
     * Controller ID
     *
     * IndexController ID is 'index'
     *
     * @var string
     */
    public string $id;

    /**
     * Called action ID
     *
     * @var string
     */
    public string $actionId;

    /**
     * Views Layout. Default layout /views/layouts/main.php
     *
     * @var string
     */
    public string $layout = 'main';

    /**
     * Base controller name without namespace and extension, such as "IndexController"
     *
     * @var string
     */
    protected string $controllerName;

    /**
     * The exact name of the action method in the format 'actionIndex'
     *
     * @var string
     */
    protected string $actionMethodName;

    /**
     * The set of all arguments being sent to the called controller's action.
     *
     * @var array
     */
    protected array $actionArgs = [];

    /**
     * The set of all required parameters for the called action method.
     * Method parameters retrieved using ReflectionMethod.
     *
     * @var array
     */
    protected array $actionRequiredParameters = [];

    /**
     * The set of all optional parameters for the called action method.
     * Method parameters retrieved using ReflectionMethod.
     *
     * @var array
     */
    protected array $actionOptionalParameters = [];

    /**
     * Component related to rendering of a given website page.
     *
     * @var View|null
     */
    private ?View $view = null;

    /**
     * Class Constructor
     *
     * @throws InvalidControllerException
     */
    public function __construct()
    {
        $baseName = ClassHelper::getClassBaseName($this);

        if(strpos($baseName, 'Controller') === false){
            throw new InvalidControllerException(sprintf('Invalid controller base name %s', $baseName));
        }

        $this->id = strtolower(str_replace('Controller', '', $baseName));
        $this->controllerName = get_class($this);

        parent::__construct();
    }

    /*
     * The beforeAction method launches immediately before the actual action of the controller.
     */
    public function beforeAction()
    {
    }

    /**
     * The main method that calls the controller action and passes the arguments.
     *
     * @param string $actionId
     * @param array $args
     * @return array|string
     * @throws InvalidActionException
     */
    public function runAction(string $actionId, array $args = []): array|string
    {
        $this->actionId = $actionId;
        $this->setActionMethodName();

        if(empty($this->actionId) || !$this->actionMethodExists()){
            throw new InvalidActionException($this->actionId, $this->controllerName);
        }

        $this->setActionParametersArray();
        $this->setActionArgs($args);

        $actionArgs = $this->bindActionParameters(true);

        try {
            ob_start();

            $this->beforeAction();

            $return = call_user_func_array([$this, $this->actionMethodName], $actionArgs);
            $content = ob_get_contents();

            ob_end_clean();

            if(is_array($return)){
                return $return;
            }

            return $content . $return;

        } catch (\ArgumentCountError $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Sets for the action method name $actionMethodName.
     *
     * @return void
     */
    public function setActionMethodName() :void
    {
        $this->actionMethodName = 'action' . ucfirst(InflectorHelper::idToCamelCase($this->actionId));
    }

    /**
     * Checks whether the called action class method exists.
     *
     * @return bool
     */
    public function actionMethodExists() :bool
    {
        return method_exists($this, $this->actionMethodName);
    }

    /**
     * Sets the required $actionRequiredParameters and optional $actionOptionalParameters parameters of the called action method.
     *
     * @return void
     * @throws \ReflectionException
     */
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

    /**
     * Renders a view. It allows sending parameters to the view which are then available as php variables.
     *
     * @param string $view
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function render(string $view, array $params = []) :string
    {
        if($this->view === null){
            $this->view = \App::$get->createView();
        }

        return $this->view->render($view, $params);
    }

    public function getViewPath(): string
    {
        return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->id;
    }

    public function getLayoutFile(): string
    {
        return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR .  'layouts' . DIRECTORY_SEPARATOR . $this->layout;
    }

    final protected function setActionArgs(array $args) :void
    {
        $this->actionArgs = $args;
    }

    /**
     * Binds sent arguments with the required methods parameters.
     * Filters out all unknown named parameters by default.
     *
     * @param bool $filterUnknownNamedParameters
     * @return array
     * @throws MissingArgumentException
     */
    protected function bindActionParameters(bool $filterUnknownNamedParameters = false): array
    {
        $actionArgs = $this->actionArgs;

        foreach ($this->actionRequiredParameters as $name => $type){
            if(!isset($actionArgs[$name])){

                $fk = array_key_first($actionArgs);

                if(!is_int($fk)){
                    throw new MissingArgumentException(sprintf('Missing required parameter: "%s"', $name));
                }

                $actionArgs[$name] = $actionArgs[$fk];
                unset($actionArgs[$fk]);
            }
        }

        if($filterUnknownNamedParameters){
            $this->filterArgs($actionArgs);
        }

        return $actionArgs;
    }

    /**
     * Filters out all unknown named parameters by comparing the passed arguments with the method parameters.
     *
     * @param array $args
     * @return void
     */
    final protected function filterArgs(array &$args): void
    {
        $args = array_intersect_key($args,
            array_merge($this->actionRequiredParameters, $this->actionOptionalParameters)
        );
    }
}