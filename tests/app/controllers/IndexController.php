<?php

declare(strict_types=1);

namespace tests\app\controllers;

use mgine\console\Controller;

/**
 * IndexController
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class IndexController extends Controller
{
    public function actionIndex()
    {
        echo ':DDD';
    }
}