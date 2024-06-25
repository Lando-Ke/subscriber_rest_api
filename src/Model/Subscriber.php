<?php

namespace App\Model;

use App\Library\Database\Contracts\DatabaseConnectionInterface;


class Subscriber extends BaseModel
{
    public function __construct(DatabaseConnectionInterface $connection)
    {
        $tableName = 'subscribers';
        parent::__construct($connection, $tableName);
    }

    public function findByEmail(string $email): array|false
    {
        return $this->find('email', $email);
    }

    public function getNameById($id)
    {
        $subscriber = $this->find('id', $id);

        return $subscriber['name'];
    }

    /**
     * Get a paginated list of subscribers.
     *
     * @param int $page Page number.
     * @param int $perPage Number of subscribers per page.
     * @return array|false Array of subscribers or false on failure.
     */
    public function getPaginatedSubscribers(int $page = 1, int $perPage = 10): array|false
    {
        return $this->paginate($page, $perPage);
    }

    /**
     * Store a new subscriber.
     *
     * @param array $data Subscriber data.
     * @return int The ID of the newly created subscriber.
     */
    public function addSubscriber(array $data): int
    {
        return $this->store($data);
    }
}
