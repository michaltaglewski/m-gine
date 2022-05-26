<?php

namespace mgine\base;

class BaseView
{
    public array $js = [];

    protected string $viewFileExtension = 'php';

    public function render(string $view, array $params = []): string
    {
        if (\App::$get->controller !== null) {
            $file = \App::$get->controller->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        } else {
            throw new \Exception("Unable to locate view file for view '$view': no active controller.");
        }

        $layoutFile = \App::$get->controller->getLayoutFile();

        return $this->renderFile($layoutFile, [
            'content' => $this->renderFile($file, $params)
        ]);
    }

    public function renderFile(string $viewFile, array $params = []) :string
    {
        $file = $viewFile . '.' . $this->viewFileExtension;

        if(!file_exists($file)){
            throw new \Exception(sprintf('View file %s does not exist', $file));
        }

        return $this->renderPhpFile($file, $params);
    }

    public function renderPhpFile(string $file, array $params = []): string
    {
        ob_start();
        ob_implicit_flush(false);

        extract($params, EXTR_OVERWRITE);

        require $file;
        return ob_get_clean();
    }

//    public function renderPhpFile($file, $params = [])
//    {
//        $_obInitialLevel_ = ob_get_level();
//        ob_start();
//        ob_implicit_flush(false);
//        extract($params, EXTR_OVERWRITE);
//        try {
//            require $file;
//            return ob_get_clean();
//        } catch (\Exception $e) {
//            while (ob_get_level() > $_obInitialLevel_) {
//                if (!@ob_end_clean()) {
//                    ob_clean();
//                }
//            }
//            throw $e;
//        } catch (\Throwable $e) {
//            while (ob_get_level() > $_obInitialLevel_) {
//                if (!@ob_end_clean()) {
//                    ob_clean();
//                }
//            }
//            throw $e;
//        }
//    }

}