<?php

namespace App\Core\Domain\Model\User;

Interface UniqueUsernameConstraint
{
    public function isValid(string $userName): bool;
}
