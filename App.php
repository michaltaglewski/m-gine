<?php

declare(strict_types=1);

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
    public static string $name;

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
}
