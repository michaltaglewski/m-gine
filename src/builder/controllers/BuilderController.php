<?php

namespace mgine\builder\controllers;

use mgine\helpers\FileHelper;

/**
 * BuilderController
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class BuilderController extends \mgine\console\Controller
{
    /**
     * @var string
     */
    protected string $builderDir;

    /**
     * @var string
     */
    protected string $rootPath;

    /**
     * @var string
     */
    protected string $appRootPath;

    /**
     * @var string
     */
    protected string $vendorDir;

    public function beforeAction()
    {
        $this->builderDir = dirname(__DIR__);
        $this->vendorDir = \App::$get->params['vendorDir'];
        $this->rootPath = \App::$get->params['rootPath'];
        $this->appRootPath = \App::$get->params['appRootPath'];
    }

    /**
     * Copies /builder/assets File to the Application destination.
     *
     * @param string $name
     * @param string $assetsFilename
     * @param string $destinationFilename
     * @return bool
     */
    protected function copyFile(string $name, string $assetsFilename, string $destinationFilename): bool
    {
        print "Copying asset file '$name' to '$destinationFilename'... ";

        $root = str_starts_with($assetsFilename, '/') ? $this->rootPath : $this->builderDir . '/assets';

        $builderAssetsFile = $root . '/' . $assetsFilename;
        $appFile = $this->appRootPath . '/' . $destinationFilename;

        FileHelper::makeDirIfNotExists(dirname($appFile));

        if(is_file($appFile)){
            $this->setTextColor('yellow');
            $this->printLine("Already exists. Skipped.");
            $this->resetTextColor();

            return false;
        }

        if(!is_file($builderAssetsFile)){
            $this->setTextColor('yellow');
            $this->printLine("Could not find '$assetsFilename' file. Skipped.");
            $this->resetTextColor();

            return false;
        }

        if(copy($builderAssetsFile, $appFile)){
            $this->setTextColor('green');
            $this->printLine('Copied successfully.');
            $this->resetTextColor();

            return true;
        } else {
            $this->setTextColor('red');
            $this->printLine('Failed.');
            $this->resetTextColor();

            return false;
        }
    }

    /**
     * Copies vendor package to the Application destination.
     *
     * @param string $name
     * @param string $vendorPackage
     * @param string $destination
     * @return bool
     */
    protected function copyVendorPackage(string $name, string $vendorPackage, string $destination): bool
    {
        print "Copying '$name' from Vendor Directory... ";

        $bootstrapDist = $this->vendorDir . $vendorPackage;
        $publicAssetsDir = $this->appRootPath . $destination;

        if(is_dir($publicAssetsDir)){
            $this->setTextColor('yellow');
            $this->printLine("Already exists. Skipped.");
            $this->resetTextColor();

            return false;
        }

        if(!is_dir($bootstrapDist)){
            $this->setTextColor('yellow');
            $this->printLine("Could not find '$name' directory. Skipped.");
            $this->resetTextColor();

            return false;
        }

        FileHelper::makeDirIfNotExists($publicAssetsDir, recursive:true);
        FileHelper::copyDirectoryRecursive($bootstrapDist, $publicAssetsDir);

        if(is_dir($publicAssetsDir)){
            $this->setTextColor('green');
            $this->printLine('Copied successfully.');
            $this->resetTextColor();

            return false;
        } else {
            $this->setTextColor('red');
            $this->printLine('Failed.');
            $this->resetTextColor();

            return false;
        }
    }

}