<?php

namespace App\Tests\Unit\Core\Application\Command\CreateUser;

use App\Core\Application\Command\CreateUser\CreateUserCommand;
use App\Core\Application\Command\CreateUser\CreateUserCommandHandler;
use App\Core\Infrastructure\InMemory\InMemoryUserRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class CreateUserCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function is_able_to_create_user()
    {
        $command = new CreateUserCommand('john', 'doe');

        $repo = new InMemoryUserRepository();

        $passwordEncoderFactory =
            $this->createMock(EncoderFactoryInterface::class);
        $passwordEncoder        =
            $this->createMock(PasswordEncoderInterface::class);
        $passwordEncoderFactory->expects($this->once())
            ->method('getEncoder')
            ->willReturn($passwordEncoder);
        $passwordEncoder->expects($this->once())
            ->method('encodePassword')
            ->with('doe')
            ->willReturn('encoded');

        $handler = new CreateUserCommandHandler($passwordEncoderFactory, $repo);
        $handler($command);

        $user = $repo->findByUsername('john');

        Assert::assertEquals('encoded', $user->getPassword());
        Assert::assertEquals('john', $user->getUsername());
    }
}
