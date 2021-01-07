<?php

namespace App\Core\Application\Command\CreateTask;

use App\Core\Domain\Model\Task\Status;
use Ramsey\Uuid\UuidInterface;

final class CreateTaskCommand
{
    private string $name;

    private UuidInterface $userId;

    private string $status;

    public function __construct(string $name, UuidInterface $userId, string $status = Status::UNDONE)
    {
        $this->name = $name;
        $this->userId = $userId;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return UuidInterface
     */
    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
