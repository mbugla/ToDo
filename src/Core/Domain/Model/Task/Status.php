<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\Task;

use ReflectionClass;

final class Status
{
    public const DONE   = 'done';
    public const UNDONE = 'undone';

    public static function getAll(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
