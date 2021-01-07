<?php
declare(strict_types=1);

namespace App\Core\Application\Command\ChangeTaskStatus;

use Ramsey\Uuid\UuidInterface;


final class TaskUndoneCommand
{
    /**
     * @var UuidInterface
     */
    private UuidInterface $taskId;

    public function __construct(UuidInterface $taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * @return UuidInterface
     */
    public function getTaskId(): UuidInterface
    {
        return $this->taskId;
    }
}
