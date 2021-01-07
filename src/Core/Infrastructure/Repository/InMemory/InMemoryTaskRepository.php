<?php

namespace App\Core\Infrastructure\Repository\InMemory;

use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Shared\Domain\Model\DomainEvent;
use Ramsey\Uuid\UuidInterface;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    /** @var DomainEvent[] */
    private array $events = [];

    public function findByUuid(UuidInterface $id): ?Task
    {
        $task = new Task($id);
        foreach ($this->events as $taskEvent) {
            $task->apply($taskEvent);
        }

        return $task;
    }

    public function add(Task $task): void
    {
        foreach ($task->getPendingEvents() as $domainEvent) {
            $this->events[$task->getId()->toString()][] = $domainEvent;
        }
    }

    public function remove(Task $task): void
    {
        unset($this->events[$task->getId()->toString()]);
    }
}
