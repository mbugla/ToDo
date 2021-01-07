<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\User;

use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function findByUuid(UuidInterface $id): ?User;

    public function findByUsername(string $username): ?User;

    public function add(User $user): void;

    public function remove(User $user): void;
}
