<?php

namespace App\Core\Domain\Model\Task\Event;

use App\Shared\Domain\Model\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final class AssignedUserChangedEvent implements DomainEvent
{
    const TYPE = 'assigned_user_changed';

    private UuidInterface $aggregateId;

    private UuidInterface $userId;

    private DateTimeImmutable $createdAt;

    public function __construct(
        UuidInterface $aggregateId,
        UuidInterface $userId,
        DateTimeImmutable $createdAt
    ) {
        $this->aggregateId = $aggregateId;
        $this->userId      = $userId;
        $this->createdAt   = $createdAt;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getValue()
    {
        return $this->userId;
    }
}
