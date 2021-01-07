<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\Task\Event;

use App\Shared\Domain\Model\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class NameChangedEvent implements DomainEvent
{
    const TYPE = 'name_changed';

    /**
     * @var UuidInterface
     */
    private UuidInterface $aggregateId;

    private string $name;

    private DateTimeImmutable $createdAt;

    /**
     * NameChangedEvent constructor.
     *
     * @param UuidInterface $aggregateId
     * @param string        $name
     */
    public function __construct(UuidInterface $aggregateId, string $name, DateTimeImmutable $createdAt)
    {

        $this->aggregateId = $aggregateId;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public function getAggregateId(): UuidInterface
    {
        return $this->aggregateId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getValue()
    {
        return $this->name;
    }
}
