<?php

namespace mgine\base;

/**
 * FileHelper
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class FileHelper
{
    /**
     * @param string $filename
     * @param mixed $data
     * @param bool $override
     * @return bool
     */
    public static function createFile(string $filename, mixed $data, bool $override = false): bool
    {
        if(!$override && is_file($filename)){
            return false;
        }

        if (file_put_contents($filename, $data)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $fileDirname
     * @return bool
     */
    public static function makeDirIfNotExists(string $fileDirname, $permissions = 0777, $recursive = false): bool
    {
        if (!is_dir($fileDirname)) {
            return mkdir($fileDirname, $permissions, $recursive);
        }

        return false;
    }

    public static function  copyDirectoryRecursive(string $sourceDirectory, string $destinationDirectory, string $childFolder = ''): void
    {
        $directory = opendir($sourceDirectory);

        if (is_dir($destinationDirectory) === false) {
            mkdir($destinationDirectory);
        }

        if ($childFolder !== '') {
            if (is_dir("$destinationDirectory/$childFolder") === false) {
                mkdir("$destinationDirectory/$childFolder");
            }

            while (($file = readdir($directory)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_dir("$sourceDirectory/$file") === true) {
                    self::copyDirectoryRecursive("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                } else {
                    copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                }
            }

            closedir($directory);

            return;
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                self::copyDirectoryRecursive("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
            else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }

        closedir($directory);
    }
}