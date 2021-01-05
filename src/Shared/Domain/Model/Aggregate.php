<?php

namespace App\Shared\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class Aggregate
{
    protected $id;

    /**
     * @var DomainEvent[]
     */
    private array $events;

    public function getId(): UuidInterface
    {
        if (is_string($this->id)) {
            $this->id = Uuid::fromString($this->id);
        }

        return $this->id;
    }

    protected function raise(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
