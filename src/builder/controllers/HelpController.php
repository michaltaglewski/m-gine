<?php

namespace mgine\builder\controllers;

class HelpController extends \mgine\console\Controller
{
    public static array $commandsList = [
        'project-create' => [
            'route' => 'project/create',
            'description' => 'create a new project with parameter "name".'
        ],
        'project-init' => [
            'route' => 'project/init',
            'description' => 'initialize a new project in current directory.'
        ]
    ];

    public function actionIndex()
    {
        $this->printLine('Available commands: ');

        foreach (self::$commandsList as $command => $data){
            $this->addTextColor($command, 'green');
            $this->printLine($command . "\t\t" . $data['description']);
        }
    }
}