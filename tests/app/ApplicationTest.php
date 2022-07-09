<?php

declare(strict_types=1);

/**
 * ApplicationTest
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    public string $basePath;

    public function setUp(): void
    {
        $this->basePath = __DIR__;
    }

    public function testAppHelper()
    {
        // Assert correct basePath
        $this->assertEquals($this->basePath, App::$basePath);
    }
}