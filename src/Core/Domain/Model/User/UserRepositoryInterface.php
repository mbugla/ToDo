<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\User;

use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function find(UuidInterface $id): ?User;

    public function findUserByUserName(string $username): ?User;

    public function add(User $user): void;

    public function remove(User $user): void;
}
