<?php

namespace mgine\builder\controllers;

use mgine\builder\File;
use mgine\builder\ProjectBuilder;
use mgine\builder\pattern\MvcPattern;

class ProjectController extends \mgine\console\Controller
{
    protected array $mvcFiles = [
        ['id' => 'controller'],
        [
            'id' => 'view',
            'file' => [
                'directory' => '/index',
                'name' => 'index',
            ]
        ],
        [
            'id' => 'view',
            'file' => [
                'directory' => 'views/layouts',
                'name' => 'main',
                'template' => 'layout',
            ]
        ],
    ];

    public function actionCreate($name)
    {
        return 'Create project: ' . $name;
    }

    public function actionInit()
    {
        $namespace = 'app';
        $rootPath = 'G:\nginx-1.20.2\html\kurs';

        $mvc = new MvcPattern($this->mvcFiles);

        $builder = new ProjectBuilder([
            'namespace' => $namespace,
            'rootPath' => $rootPath,
        ]);

        $builder->setPattern($mvc);

        /** @var File $file */

        foreach ($builder->generate() as $path => $file){

            print sprintf('Creating %s ... ', $path);

            if($file->save($rootPath)){
                $this->printLine('done', 'green');
            } else {
                $this->printLine('failed', 'red');
            }
        }
    }
}