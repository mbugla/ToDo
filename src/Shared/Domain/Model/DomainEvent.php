<?php

namespace App\Shared\Domain\Model;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface DomainEvent
{
    public static function getType(): string;

    public function getAggregateId(): UuidInterface;

    public function getCreatedAt(): DateTimeImmutable;

    public function getValue();
}
