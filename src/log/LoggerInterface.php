<?php

declare(strict_types=1);

namespace mgine\log;

/**
 * LoggerInterface
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
interface LoggerInterface
{
    public function log(string $type, string $message): void;
}