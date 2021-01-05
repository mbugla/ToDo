<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\Task\Event;

use App\Shared\Domain\Model\DomainEvent;
use Ramsey\Uuid\UuidInterface;

class StatusChangedEvent implements DomainEvent
{
    private UuidInterface $aggregateId;

    private string $status;

    public function __construct(UuidInterface $aggregateId, string $status)
    {
        $this->aggregateId = $aggregateId;
        $this->status      = $status;
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
}
