<?php

namespace App\Core\Domain\Model\Task;

use Ramsey\Uuid\UuidInterface;

interface TaskRepositoryInterface
{
    /**
     * @param UuidInterface $userId
     *
     * @return array|Task[]
     */
    public function findByUser(UuidInterface $userId): array;

    public function findByUuid(UuidInterface $id): ?Task;

    public function save(Task $task): void;

    public function remove(Task $task): void;

}
