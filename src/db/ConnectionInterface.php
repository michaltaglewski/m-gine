<?php

namespace mgine\db;

interface ConnectionInterface
{
    public function connect(): bool;

    public function query(string $sql);

    public function execute();
}