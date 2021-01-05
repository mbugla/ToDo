<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\Task\Event;

use App\Shared\Domain\Model\DomainEvent;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class NameChangedEvent implements DomainEvent
{
    /**
     * @var UuidInterface
     */
    private UuidInterface $aggregateId;

    private string $name;

    /**
     * NameChangedEvent constructor.
     *
     * @param UuidInterface $aggregateId
     * @param string        $name
     */
    public function __construct(UuidInterface $aggregateId, string $name)
    {

        $this->aggregateId = $aggregateId;
        $this->name = $name;
    }

    public function getAggregateId(): UuidInterface
    {
        return $this->aggregateId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
