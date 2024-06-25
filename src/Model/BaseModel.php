<?php

namespace App\Model;

use App\Library\Database\Contracts\DatabaseConnectionInterface;

class BaseModel
{
    protected DatabaseConnectionInterface $connection;

    protected string $table;

    public function __construct(DatabaseConnectionInterface $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function find(string $field, $value): array|false
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `$field` = :value";

        return $this->connection->query($sql, [':value' => $value])->fetch();
    }

    public function store(array $data): int
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':'.implode(', :', array_keys($data));
        $sql = "INSERT INTO `{$this->table}` ($fields) VALUES ($placeholders)";

        return $this->connection->insert($sql, $data);
    }

    /**
     * Get a paginated list of records and the total count.
     *
     * @param int $page Page number.
     * @param int $perPage Number of records per page.
     * @return array Array containing records and total count.
     */
    public function paginate(int $page = 1, int $perPage = 10): array
    {
        if (! is_numeric($page) || ! is_numeric($perPage)) {
            throw new \InvalidArgumentException('Page and perPage must be numeric.');
        }

        $start = intval(($page - 1) * $perPage);
        $perPage = intval($perPage);

        $sql = "SELECT * FROM `{$this->table}` LIMIT :start, :perPage";
        $items = $this->connection->query($sql, [':start' => $start, ':perPage' => $perPage])->fetchAll();

        $totalSql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        $totalResult = $this->connection->query($totalSql)->fetch();
        $total = $totalResult['total'] ?? 0;

        return [
            'items' => $items,
            'total' => $total,
        ];
    }
}
