<?php

namespace App\Tests\Unit\Core\Application\Command\CreateAuthToken;

use App\Core\Application\Command\CreateAuthToken\CreateAuthTokenCommand;
use App\Core\Application\Command\CreateAuthToken\CreateAuthTokenCommandHandler;
use App\Core\Domain\Model\User\User;
use App\Core\Infrastructure\Repository\InMemory\InMemoryUserRepository;
use App\Tests\Unit\Core\Domain\Model\User\UserTest;
use InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAuthTokenCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_token_for_valid_user()
    {
        $user       = new User(
            Uuid::uuid4(),
            'john',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );
        $repository = new InMemoryUserRepository();
        $repository->add($user);

        $passwordEncoder =
            $this->createMock(UserPasswordEncoderInterface::class);
        $passwordEncoder->method('isPasswordValid')->willReturn(true);
        $jwtTokenManager = $this->createMock(JWTTokenManagerInterface::class);
        $jwtTokenManager->method('create')->with($user)->willReturn('token');

        $handler = new CreateAuthTokenCommandHandler(
            $passwordEncoder,
            $repository,
            $jwtTokenManager
        );
        $command = new CreateAuthTokenCommand('john', 'pass');

        $token = $handler($command);

        Assert::assertEquals('token', $token);
    }

    /**
     * @test
     */
    public function it_should_not_return_token_for_non_existing_user()
    {
        $repository = new InMemoryUserRepository();

        $passwordEncoder =
            $this->createMock(UserPasswordEncoderInterface::class);
        $jwtTokenManager = $this->createMock(JWTTokenManagerInterface::class);

        $handler = new CreateAuthTokenCommandHandler(
            $passwordEncoder,
            $repository,
            $jwtTokenManager
        );
        $command = new CreateAuthTokenCommand('john', 'pass');

        $this->expectException(InvalidArgumentException::class);
        $handler($command);
    }

    /**
     * @test
     */
    public function it_should_not_return_token_for_invalid_user_password()
    {
        $user       = new User(
            Uuid::uuid4(),
            'john',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );
        $repository = new InMemoryUserRepository();
        $repository->add($user);

        $passwordEncoder =
            $this->createMock(UserPasswordEncoderInterface::class);
        $passwordEncoder->method('isPasswordValid')->willReturn(false);
        $jwtTokenManager = $this->createMock(JWTTokenManagerInterface::class);

        $handler = new CreateAuthTokenCommandHandler(
            $passwordEncoder,
            $repository,
            $jwtTokenManager
        );
        $command = new CreateAuthTokenCommand('john', 'pass');
        $this->expectException(InvalidArgumentException::class);

        $handler($command);
    }
}
