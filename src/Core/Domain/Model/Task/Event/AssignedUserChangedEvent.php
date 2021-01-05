<?php

namespace App\Core\Domain\Model\Task\Event;

use App\Shared\Domain\Model\DomainEvent;
use Ramsey\Uuid\UuidInterface;

class AssignedUserChangedEvent implements DomainEvent
{
    private UuidInterface $aggregateId;

    private UuidInterface $userId;

    public function __construct(
        UuidInterface $aggregateId,
        UuidInterface $userId
    ) {
        $this->aggregateId = $aggregateId;
        $this->userId      = $userId;
    }

    public function getAggregateId(): UuidInterface
    {
        return $this->aggregateId;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
