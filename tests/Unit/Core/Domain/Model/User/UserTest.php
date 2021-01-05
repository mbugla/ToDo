<?php

namespace App\Tests\Unit\Core\Domain\Model\User;

use App\Core\Domain\Model\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_have_username()
    {
        $user = new User(Uuid::uuid4(),'bruce', 'pass');

        Assert::assertEquals('bruce', $user->getUsername());
    }

    /**
     * @test
     */
    public function it_should_have_password()
    {
        $user = new User(Uuid::uuid4(), 'bruce', 'pass');

        Assert::assertEquals('pass', $user->getPassword());
    }

    /**
     * @test
     */
    public function it_should_have_role()
    {
        $user = new User(Uuid::uuid4(),'bruce', 'pass');

        Assert::assertEquals([User::DEFAULT_USER_ROLE], $user->getRoles());
    }

    /**
     * @test
     */
    public function it_has_defined_created_date()
    {
        $user = new User(Uuid::uuid4(),'bruce', 'pass');

        Assert::assertInstanceOf(
            DateTimeImmutable::class,
            $user->getCreatedAt()
        );
    }
}
