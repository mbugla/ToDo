<?php

namespace App\Shared\Domain\Model;

use Ramsey\Uuid\UuidInterface;

interface DomainEvent
{
    public function getAggregateId(): UuidInterface;
}
