<?php

namespace mgine\base;

use mgine\helpers\{ClassHelper, ArrayHelper};

/**
 * Composer
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Composer
{
    /**
     * @var string
     */
    public static string $rootPackage = 'michaltaglewski/m-gine';

    /**
     * @return array|null
     */
    public static function getRootPackage(): ?array
    {
        $installed = self::getInstalled();

        return $installed[self::$rootPackage] ?? null;
    }

    /**
     * @return array
     */
    public static function getInstalledPackages(): array
    {
        $installed = self::getInstalled();

        unset($installed[self::$rootPackage]);

        return $installed;
    }

    /**
     * @return array
     */
    public static function getInstalled(): array
    {
        $composerDir = ClassHelper::getClassDirectory('Composer\InstalledVersions');

        $json = file_get_contents($composerDir . '/installed.json');

        $installed = json_decode($json, true);

        if(is_array($installed['packages'])){
            return ArrayHelper::indexByColumn($installed['packages'],'name');
        }

        return [];
    }
}