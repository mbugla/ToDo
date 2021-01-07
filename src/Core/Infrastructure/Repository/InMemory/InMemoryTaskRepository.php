<?php

namespace App\Core\Infrastructure\Repository\InMemory;

use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Shared\Domain\Model\DomainEvent;
use Ramsey\Uuid\UuidInterface;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    /** @var DomainEvent[][] */
    private array $events = [];

    /** @var Task[][] */
    private array $userTasks = [];

    /**
     * @param UuidInterface $userId
     *
     * @return array|Task[]
     */
    public function findByUser(UuidInterface $userId): array
    {
        if (array_key_exists($userId->toString(), $this->userTasks)) {
            return $this->userTasks[$userId->toString()];
        }

        return [];
    }

    public function findByUuid(UuidInterface $id): ?Task
    {
        if (!array_key_exists($id->toString(), $this->events)) {
            return null;
        }

        if (count($this->events[$id->toString()]) > 0) {
            return Task::recreateFrom($id, $this->events[$id->toString()]);
        }

        return null;
    }

    public function save(Task $task): void
    {
        foreach ($task->getPendingEvents() as $domainEvent) {
            $this->events[$task->getId()->toString()][] = $domainEvent;
        }

        $this->userTasks[$task->getUserId()->toString()][] = $task;

        $task->eventsFlushed();
    }

    public function remove(Task $task): void
    {
        unset($this->events[$task->getId()->toString()]);
    }
}
