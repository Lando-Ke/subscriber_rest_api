<?php

namespace App\Library\Database;

use PDOStatement;
use App\Library\Database\Methods\MySqlDatabase;
use App\Library\Database\Contracts\DatabaseConnectionInterface;
use App\Library\Database\Contracts\DatabaseConnectionMethodInterface;

class Connection implements DatabaseConnectionInterface
{
    protected DatabaseConnectionMethodInterface $method;

    public function __construct(?DatabaseConnectionMethodInterface $method = null)
    {
        $this->uses($method);
    }

    public function uses(?DatabaseConnectionMethodInterface $method = null)
    {
        $this->method = $method ?? $this->defaultMethod();

        return $this;
    }

    public function method(): DatabaseConnectionMethodInterface
    {
        return $this->method ?? $this->defaultMethod();
    }

    protected function defaultMethod(): DatabaseConnectionMethodInterface
    {
        return new MySqlDatabase();
    }

    public function query(string $sql, array $parameters = []): PDOStatement
    {
        return $this->method()->query($sql, $parameters);
    }

    public function insert(string $sql, array $parameters = []): int
    {
        return $this->method()->insert($sql, $parameters);
    }
}
