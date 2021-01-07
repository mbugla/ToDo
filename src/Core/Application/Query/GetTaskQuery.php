<?php
declare(strict_types=1);

namespace App\Core\Application\Query;

use Ramsey\Uuid\UuidInterface;

final class GetTaskQuery
{
    /**
     * @var UuidInterface
     */
    private UuidInterface $taskId;

    /**
     * @var UuidInterface
     */
    private UuidInterface $userId;

    function __construct(UuidInterface $taskId, UuidInterface $userId)
    {
        $this->taskId = $taskId;
        $this->userId = $userId;
    }

    /**
     * @return UuidInterface
     */
    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    /**
     * @return UuidInterface
     */
    public function getTaskId(): UuidInterface
    {
        return $this->taskId;
    }
}
