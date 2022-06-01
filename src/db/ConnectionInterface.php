<?php

namespace mgine\db;

/**
 * ConnectionInterface
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
interface ConnectionInterface
{
    public function connect(): bool;

    public function query(string $sql);

    public function execute();
}