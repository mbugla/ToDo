<?php
declare(strict_types=1);

namespace App\Core\Application\Command\ChangeTaskStatus;

use Ramsey\Uuid\UuidInterface;


class ChangeTaskStatusCommand
{
    /**
     * @var UuidInterface
     */
    private UuidInterface $taskId;

    private string $status;

    public function __construct(UuidInterface $taskId, string $status)
    {
        $this->taskId = $taskId;
        $this->status = $status;
    }

    /**
     * @return UuidInterface
     */
    public function getTaskId(): UuidInterface
    {
        return $this->taskId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
