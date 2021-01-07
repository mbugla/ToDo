<?php

namespace App\Shared\Domain\Model;

use Ramsey\Uuid\UuidInterface;

interface DomainEvent
{
    public static function getType(): string;

    public function getAggregateId(): UuidInterface;
}
