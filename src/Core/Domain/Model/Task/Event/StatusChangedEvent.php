<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\Task\Event;

use App\Shared\Domain\Model\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final class StatusChangedEvent implements DomainEvent
{
    const TYPE = 'status_changed';

    private UuidInterface $aggregateId;

    private string $status;

    private DateTimeImmutable $createdAt;

    public function __construct(
        UuidInterface $aggregateId,
        string $status,
        DateTimeImmutable $createdAt
    ) {
        $this->aggregateId = $aggregateId;
        $this->status      = $status;
        $this->createdAt   = $createdAt;
    }

    public function getAggregateId(): UuidInterface
    {
        return $this->aggregateId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
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
        return $this->status;
    }
}
