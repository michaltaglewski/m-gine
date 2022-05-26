<?php

namespace mgine\db;

use mgine\base\Component;

abstract class Connection extends Component
{
    public object|null $connection = null;

    public string $username;

    public string $password;

    public string $host;

    public string $dbName;

    public string $charset = 'utf8';

    public int $port = 3306;

    public string $queryString;

    public bool $successful = false;

    public bool $connectionEstablished;

    public ?string $connectionError;

//    public function __construct()
//    {
////        $this->configure($config);
//
////        $this->selectConnectionEngine();
//
//        if(!$this->connection){
//            $this->connectionEstablished = false;
//
//            throw new \Exception('Failed to establish DB connection: ' . $this->connectionError);
//        }
//
//        if($this->connection->error){
//            $this->connectionError = $this->connection->error;
//
//            throw new \Exception('Connection error: ' . $this->connectionError);
//        }
//    }

//    public function selectConnectionEngine()
//    {
//        $this->connection = new MysqlConnection($this->config);
//    }

    public function query(string $queryString)
    {
        $this->queryString = $queryString;
        $this->connection->query($this->queryString);

        return $this;
    }

    public function execute()
    {
        return $this->connection->execute();
    }
}