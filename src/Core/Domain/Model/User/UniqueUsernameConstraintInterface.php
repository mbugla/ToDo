<?php

namespace App\Core\Domain\Model\User;

Interface UniqueUsernameConstraintInterface
{
    public function isUnique(string $username): bool;
}
