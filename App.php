<?php

declare(strict_types=1);

use mgine\base\{Application, Loader};

/**
 * M-Gine main Application helper
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class App
{
    /**
     * @var string
     */
    public static string $name = 'app';

    /**
     * @var string
     */
    public static string $basePath;

    /**
     * @var string
     */
    public static string $baseURL;

    /**
     *
     * @var \mgine\web\Application | \mgine\console\Application  | \mgine\base\Application
     */
    public static mgine\base\Application $get;

    /**
     * @var Loader
     */
    public static Loader $loader;

    /**
     * @param Application $app
     * @return void
     */
    public static function init(Application $app)
    {
        $app->path = self::getPath();
        $app->basePath = self::$basePath;

        self::$get = $app;
    }

    /**
     * @param string|null $path
     * @return string
     */
    public static function getPath(string $path = null): string
    {
        return realpath(self::$basePath . '/' . self::$name . '/' . $path) ?:
            realpath(self::$basePath . $path) ?: self::$basePath;
    }

	/**
	 * @param array $namespaces
     * @return void
     */
    public static function autoload(array $namespaces = []): void
    {
        self::$loader = new Loader();
        
        self::$loader->registerNamespaces(
            array_merge([self::$name => self::getPath()], $namespaces)
        );

        self::$loader->register();
    }
}
