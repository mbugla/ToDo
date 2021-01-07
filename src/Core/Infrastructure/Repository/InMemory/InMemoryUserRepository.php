<?php

namespace App\Core\Infrastructure\Repository\InMemory;

use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserRepositoryInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var User[] */
    private array $users = [];

    public function findByUuid($id): ?User
    {
        if (array_key_exists($id->toString(), $this->users)) {
            return $this->users[$id->toString()];
        }

        return null;
    }

    public function findByUsername(string $username): ?User
    {
        foreach ($this->users as $id => $user) {
            if ($user->getUsername() === $username) {
                return $user;
            }
        }

        return null;
    }

    public function add(User $user): void
    {
        $this->users[$user->getId()->toString()] = $user;
    }

    public function remove(User $user): void
    {
        unset($this->users[$user->getId()->toString()]);
    }
}
