<?php

namespace App\Library\Database\Contracts;

interface DatabaseConnectionInterface extends DatabaseConnectionMethodInterface
{
    public function uses(DatabaseConnectionMethodInterface $method);

    public function method(): DatabaseConnectionMethodInterface;
}