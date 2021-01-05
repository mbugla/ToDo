<?php
declare(strict_types=1);

namespace App\Core\Application\Command\CreateAuthToken;

use App\Core\Domain\Model\User\UserRepositoryInterface;
use InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class CreateAuthTokenCommandHandler
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    private UserRepositoryInterface $userRepository;

    private JWTTokenManagerInterface $JWTTokenManager;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        UserRepositoryInterface $userRepository,
        JWTTokenManagerInterface $JWTTokenManager
    ) {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userRepository      = $userRepository;
        $this->JWTTokenManager     = $JWTTokenManager;
    }

    public function __invoke(CreateAuthTokenCommand $command): string
    {
        $user =
            $this->userRepository->findUserByUserName($command->getUsername());

        if ($user === null) {
            throw new InvalidArgumentException('Invalid credentials');
        }

        if (!$this->userPasswordEncoder->isPasswordValid(
            $user,
            $command->getPassword()
        )) {
            throw new InvalidArgumentException('Invalid credentials');
        }

        return $this->JWTTokenManager->create($user);
    }
}
