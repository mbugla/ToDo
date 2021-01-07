<?php

namespace App\Core\Domain\Model\Task;

use App\Core\Domain\Model\Task\Event\AssignedUserChangedEvent;
use App\Core\Domain\Model\Task\Event\NameChangedEvent;
use App\Core\Domain\Model\Task\Event\StatusChangedEvent;
use App\Core\Domain\Model\Task\Exception\NameToShortException;
use App\Shared\Domain\Model\Aggregate;
use App\Shared\Domain\Model\DomainEvent;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

final class Task extends Aggregate
{
    const NAME_MIN_LENGTH = 2;

    private ?string $name;

    private ?UuidInterface $userId;

    private string $status;

    public function __construct(
        UuidInterface $uuid,
        UuidInterface $userId = null,
        string $name = null,
        string $status = Status::UNDONE
    ) {
        $this->id = $uuid;
        if ($name) {
            $this->changeName($name);
        }
        if ($userId) {
            $this->assignToUser($userId);
        }
        $this->handleStatus($status);
    }

    public function changeName(string $name)
    {
        if (strlen($name) < self::NAME_MIN_LENGTH) {
            throw new NameToShortException(
                sprintf('Name should be greater than %s', self::NAME_MIN_LENGTH)
            );
        }

        $this->nameChanged(new NameChangedEvent($this->getId(), $name));
    }

    public function assignToUser(UuidInterface $userId): void
    {
        $this->assignedUserChanged(
            new AssignedUserChangedEvent($this->getId(), $userId)
        );
    }

    public function markAsDone()
    {
        $this->statusChanged(
            new StatusChangedEvent($this->getId(), Status::DONE)
        );
    }

    public function markAsUndone()
    {
        $this->statusChanged(
            new StatusChangedEvent($this->getId(), Status::UNDONE)
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    private function nameChanged(NameChangedEvent $event): void
    {
        $this->name = $event->getName();
        $this->raise($event);
    }

    private function statusChanged(StatusChangedEvent $event): void
    {
        $this->status = $event->getStatus();
        $this->raise($event);
    }

    private function assignedUserChanged(AssignedUserChangedEvent $event)
    {
        $this->userId = $event->getUserId();
        $this->raise($event);
    }

    public static function recreateFrom(UuidInterface $id, array $events): Task
    {
        $task = new self($id);

        foreach ($events as $event) {
            $task->apply($event);
        }

        return $task;
    }

    private function apply(DomainEvent $event): void
    {
        switch ($event->getType()) {
            case AssignedUserChangedEvent::TYPE:
                $this->assignedUserChanged($event);
                break;
            case NameChangedEvent::TYPE:
                $this->nameChanged($event);
                break;
            case StatusChangedEvent::TYPE:
                $this->statusChanged($event);
                break;
            default:
                throw new InvalidArgumentException(
                    sprintf("Type: %s not supported", $event->getType())
                );
        }
    }

    /**
     * @param string $status
     */
    private function handleStatus(string $status): void
    {
        switch ($status) {
            case Status::DONE:
                $this->markAsDone();
                break;
            case Status::UNDONE:
                $this->markAsUndone();
                break;
        }
    }
}
