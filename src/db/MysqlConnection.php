<?php

namespace mgine\db;

use mgine\helpers\ArrayHelper;
use \PDOException;
use mgine\base\Component;

/**
 * MysqlConnection @TODO
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class MysqlConnection extends Connection implements ConnectionInterface
{
    public string $dsn;

//    public string $username;

//    public string $password;

//    public string $host;

//    public string $dbName;

//    public string $charset = 'utf8';

//    public int $port = 3306;

//    public \PDO $connection;

    public \PDOStatement|false $statement;

    public bool $established;

    public ?string $error = null;

    public function init()
    {
        $this->parseDSN();
        $this->setDSNCharset();

        $this->established = $this->connect();
    }

    public function connect(): bool
    {
        try {
            $this->connection = new \PDO($this->dsn, $this->username, $this->password);
        } catch (PDOException $e) {
            $this->connectionError = $e->getMessage();

            return false;
        }

        return true;
    }

    public function query($sql)
    {
        $this->statement = $this->connection->query($sql);

        return $this;
    }

    public function execute()
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function parseDSN(): bool
    {
        if(substr($this->dsn, 0, 5) === 'mysql'
            && substr($this->dsn, 5, 1) === ':'){

            $paramsString = substr($this->dsn, 6, -1);
            $parts = ArrayHelper::stringToParamsArray($paramsString);

            if(empty($parts['host']) || empty($parts['dbname'])){
                throw new \Exception('Invalid DSN string');
            }

            $this->host = $parts['host'];
            $this->dbName = $parts['dbname'];

            return true;
        }

        throw new \Exception('Invalid DSN string');
    }

    private function setDSNCharset(): void
    {
        $this->dsn .= ';charset=' . $this->charset;
    }

}