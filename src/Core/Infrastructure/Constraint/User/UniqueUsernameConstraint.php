<?php
declare(strict_types=1);

namespace App\Core\Infrastructure\Constraint\User;

use App\Core\Domain\Model\User\UniqueUsernameConstraintInterface;
use App\Core\Domain\Model\User\UserRepositoryInterface;

class UniqueUsernameConstraint implements UniqueUsernameConstraintInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * UniqueUsernameConstraint constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function isUnique(string $username): bool
    {
        $existing = $this->userRepository->findByUsername($username);

        if ($existing) {
            return false;
        }

        return true;
    }
}
