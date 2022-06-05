<?php

namespace mgine\base;

/**
 *  BaseView
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class BaseView
{
    /**
     * @var array
     */
    public array $js = [];

    /**
     * @var string
     */
    protected string $viewFileExtension = 'php';

    /**
     * @param string $view
     * @param array $params
     * @return string
     * @throws \Exception
     */
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

    /**
     * @param string $viewFile
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function renderFile(string $viewFile, array $params = []) :string
    {
        $file = $viewFile . '.' . $this->viewFileExtension;

        if(!file_exists($file)){
            throw new \Exception(sprintf('View file %s does not exist', $file));
        }

        return $this->renderPhpFile($file, $params);
    }

    /**
     * @param string $file
     * @param array $params
     * @return string
     */
    public function renderPhpFile(string $file, array $params = []): string
    {
        ob_start();
        ob_implicit_flush(false);

        extract($params, EXTR_OVERWRITE);

        require $file;
        return ob_get_clean();
    }
}