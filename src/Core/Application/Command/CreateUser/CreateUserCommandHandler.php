<?php
declare(strict_types=1);

namespace App\Core\Application\Command\CreateUser;

use App\Core\Domain\Model\User\UniqueUsernameConstraintInterface;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class CreateUserCommandHandler implements MessageHandlerInterface
{
    private EncoderFactoryInterface $encoderFactory;

    private UserRepositoryInterface $userRepository;

    private UniqueUsernameConstraintInterface $uniqueUsernameConstraint;

    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        UserRepositoryInterface $userRepository,
        UniqueUsernameConstraintInterface $uniqueUsernameConstraint
    ) {
        $this->encoderFactory           = $encoderFactory;
        $this->userRepository           = $userRepository;
        $this->uniqueUsernameConstraint = $uniqueUsernameConstraint;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);
        $user    = new User(
            Uuid::uuid4(),
            $command->getUsername(),
            $encoder->encodePassword($command->getPassword(), null),
            $this->uniqueUsernameConstraint
        );
        $this->userRepository->add($user);
    }
}
