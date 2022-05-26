<?php

namespace mgine\web;

use mgine\base\BaseController;

class Controller extends BaseController
{
    public bool $csrfValidation = true;

    public string $layout = 'main';

    public function runAction(string $actionId, array $args = []): mixed
    {
        $this->actionId = $actionId;
        $this->setActionMethodName();

        if(empty($this->actionId) || !$this->actionMethodExists()){
            throw new BadRequestHttpException(sprintf('Action "%s" does not exist in "%s"',
                 $this->actionId, $this->controllerName
            ));
        }

        if($this->csrfValidation){
            \App::$get->request->csrfValidate();
        }

        $this->setActionParametersArray();
        $this->setActionArgs($args);
        $this->filterArgs();

        $missingParameters = $this->getActionMissingParameters();

        if(!empty($missingParameters)){
            throw new BadRequestHttpException('Missing required parameters: ' . implode(', ', $missingParameters));
        }

        try {
            ob_start();

            $this->beforeAction();

            $return = call_user_func_array([$this, $this->actionMethodName], $this->actionArgs);
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
}