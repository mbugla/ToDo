<?php

namespace App\Core\Domain\Model\Task\Event;

use App\Shared\Domain\Model\DomainEvent;
use Ramsey\Uuid\UuidInterface;

final class AssignedUserChangedEvent implements DomainEvent
{
    const TYPE = 'assigned_user_changed';

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

    public static function getType(): string
    {
        return self::TYPE;
    }
}
