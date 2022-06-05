<?php

namespace mgine\builder\controllers;

/**
 * Builder HelpController
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class HelpController extends \mgine\console\Controller
{
    /**
     * Available builder commands
     *
     * @var array|\string[][]
     */
    public static array $commandsList = [
        'create-project' => [
            'route' => 'project/create',
            'description' => 'Creates a new project. Usage: project-create [name]'
        ],
        'init-project' => [
            'route' => 'project/init',
            'description' => 'Initializes a new project in current directory. Usage: init-project'
        ],
        'create-controller' => [
            'route' => 'controller/create',
            'description' => 'Creates a Controller. Usage: create-controller [name] [namespace]'
        ],
    ];

    /**
     * Action prints available builder commands
     *
     * @return void
     */
    public function actionIndex()
    {
        $this->printLine('Available commands: ');

        foreach (self::$commandsList as $command => $data){
            $this->addTextColor($command, 'green');
            $this->printLine($command . "\t\t" . $data['description']);
        }
    }
}