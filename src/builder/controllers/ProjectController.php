<?php

namespace mgine\builder\controllers;

use mgine\builder\{
    File,
    ProjectBuilder,
    pattern\MvcPattern
};
use mgine\base\FileAlreadyExistsException;

/**
 * Builder ProjectController
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class ProjectController extends BuilderController
{
    /**
     * Action creates a new project @TODO
     *
     * @param $name
     * @return string
     */
    public function actionCreate($name)
    {
        return 'Create project: ' . $name;
    }

    /**
     * Action initiates a new project in current directory
     *
     * @throws \mgine\base\InvalidConfigException
     */
    public function actionInit()
    {
        $namespace = 'app';

        $mvc = new MvcPattern();

        $builder = new ProjectBuilder([
            'namespace' => $namespace,
            'rootPath' => $this->appRootPath,
        ]);

        $builder->setPattern($mvc);

        $this->configureProject($builder);
        $this->configureMvcProject($mvc);

        /** @var File $file */

        foreach ($builder->generate() as $path => $file){

            print sprintf("Creating '%s'... ", $path);

            try {
                if($file->save($this->appRootPath)){
                    $this->printLine('Done', 'green');
                } else {
                    $this->printLine('failed', 'red');
                }

            } catch (FileAlreadyExistsException $ex){
                $this->printLine('Already exists. Skipped.', 'yellow');
            }
        }

        $this->actionInstallAssets();
    }

    public function actionInstallAssets()
    {
        /** Bootstrap Packages */
        $this->copyVendorPackage('Bootstrap', '/twbs/bootstrap/dist', '/public/assets/bootstrap');
        $this->copyVendorPackage('Bootstrap Icons', '/twbs/bootstrap-icons/font', '/public/assets/bootstrap-icons');

        /** Web /public files */
        $this->copyFile('favicon.ico', 'public/favicon.ico', 'public/favicon.ico');
        $this->copyFile('logo.png', 'public/img/logo.png', 'public/assets/img/logo.png');
        $this->copyFile('starter-template.css', 'public/css/starter-template.css', 'public/assets/css/starter-template.css');
    }

    /**
     * Prepares the configuration for the core files project application.
     *
     * @param ProjectBuilder $builder
     * @return void
     * @throws \mgine\base\InvalidConfigException
     */
    private function configureProject(ProjectBuilder $builder)
    {
        $builder->addFileConfig('plain', [
            'directory' => 'public',
            'name' => 'index',
            'template' => 'public-index',
        ]);

        $builder->addFileConfig('plain', [
            'directory' => 'config',
            'name' => 'url-manager',
            'template' => 'config-url-manager',
        ]);

        $builder->addFileConfig('plain', [
            'directory' => 'config',
            'name' => 'db',
            'template' => 'config-db',
        ]);

        $builder->addFileConfig('plain', [
            'directory' => 'config',
            'name' => 'web',
            'template' => 'config-web',
        ]);
    }

    /**
     * Prepares the configuration for the MVC project application.
     *
     * @param MvcPattern $mvc
     * @return void
     */
    private function configureMvcProject(MvcPattern $mvc): void
    {
        /** HomeController */
        $mvc->addFileConfig('controller', [
            'className' => 'HomeController'
        ]);

        /** views/home/index */
        $mvc->addFileConfig('view', [
            'directory' => '/home',
            'name' => 'index',
            'template' => 'view-home'
        ]);

        /** views/layout/main */
        $mvc->addFileConfig('view', [
            'directory' => 'views/layouts',
            'name' => 'main',
            'template' => 'layout-bootstrap',
        ]);
    }
}