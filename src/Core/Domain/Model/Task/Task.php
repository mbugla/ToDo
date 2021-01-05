<?php

namespace App\Core\Domain\Model\Task;

use App\Core\Domain\Model\Task\Event\NameChangedEvent;
use App\Core\Domain\Model\Task\Event\StatusChangedEvent;
use App\Core\Domain\Model\Task\Exception\NameToShortException;
use App\Shared\Domain\Model\Aggregate;
use Ramsey\Uuid\UuidInterface;

final class Task extends Aggregate
{
    const NAME_MIN_LENGTH = 2;

    private string $name;

    /**
     * @var UuidInterface
     */
    private UuidInterface $userId;

    private string $status;

    public function __construct(
        UuidInterface $uuid,
        string $name,
        UuidInterface $userId,
        string $status
    ) {
        $this->id     = $uuid;
        $this->name   = $name;
        $this->userId = $userId;
        $this->status = $status;
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

    public function assignToUser(UuidInterface $userId)
    {

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
}
