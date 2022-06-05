<?php

namespace mgine\db;

/**
 * ConnectionInterface
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
interface ConnectionInterface
{
    public function connect(): bool;

    public function query(string $sql);

    public function execute();
}