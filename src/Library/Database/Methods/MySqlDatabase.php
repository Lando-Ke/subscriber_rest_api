<?php

namespace App\Library\Database\Methods;

use PDO;
use PDOException;
use PDOStatement;
use App\Library\Database\Contracts\DatabaseConnectionMethodInterface;

class MySqlDatabase implements DatabaseConnectionMethodInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->connect();
    }

    protected function connect(): void
    {
        //database details from docker
        //TODO : Move variables to env file
        $host = 'rest-api-mysql';
        $db = 'rest_api';
        $user = 'rest_api';
        $pass = 'rest_api';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function query(string $sql, array $parameters = []): PDOStatement
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($parameters);

        return $stmt;
    }

    public function insert(string $sql, array $parameters = []): int
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($parameters);

        return (int) $this->pdo()->lastInsertId();
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
