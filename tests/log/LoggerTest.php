<?php

declare(strict_types=1);

use mgine\log\{Stream, Logger};

/**
 * LoggerTest
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class LoggerTest extends \PHPUnit\Framework\TestCase
{
    public function testSteamCreationAndPurge()
    {
        $logFile = '/runtime/app.log';

        $stream = new Stream($logFile);
        $logger = new Logger($stream);

        $logger->log(Logger::INFO, 'Test Log');
        $this->assertFileExists(App::$basePath . $logFile);

        $stream->purge();
        $this->assertFileDoesNotExist(App::$basePath . $logFile);
    }
}