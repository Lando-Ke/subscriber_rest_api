<?php

namespace App\Library\Database\Contracts;

use PDOStatement;

interface DatabaseConnectionMethodInterface
{
    public function query(string $sql, array $parameters = []): PDOStatement;
    public function insert(string $sql, array $parameters = []): int;
}
