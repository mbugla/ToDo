<?php
declare(strict_types=1);

namespace App\Core\Application\Query;

use Ramsey\Uuid\UuidInterface;

final class GetTasksQuery
{
    private UuidInterface $userId;

    public function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
