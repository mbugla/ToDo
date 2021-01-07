<?php

namespace App\Tests\Unit\Core\Domain\Model\User;

use App\Core\Domain\Model\User\UniqueUsernameConstraintInterface;
use App\Core\Domain\Model\User\User;
use DateTimeImmutable;
use InvalidArgumentException;
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
        $user = new User(
            Uuid::uuid4(),
            'bruce',
            'pass',
            $this->getUniqueUsernameConstraint()
        );

        Assert::assertEquals('bruce', $user->getUsername());
    }

    /**
     * @test
     */
    public function it_should_fail_on_non_unique_username()
    {
        $this->expectException(InvalidArgumentException::class);
        new User(
            Uuid::uuid4(),
            'bruce',
            'pass',
            $this->getUniqueUsernameConstraint(false)
        );
    }

    /**
     * @test
     */
    public function it_should_have_password()
    {
        $user = new User(
            Uuid::uuid4(), 'bruce', 'pass',
            $this->getUniqueUsernameConstraint()
        );

        Assert::assertEquals('pass', $user->getPassword());
    }

    /**
     * @test
     */
    public function it_should_have_role()
    {
        $user = new User(
            Uuid::uuid4(), 'bruce', 'pass',
            $this->getUniqueUsernameConstraint()
        );

        Assert::assertEquals([User::DEFAULT_USER_ROLE], $user->getRoles());
    }

    /**
     * @test
     */
    public function it_has_defined_created_date()
    {
        $user = new User(
            Uuid::uuid4(), 'bruce', 'pass',
            $this->getUniqueUsernameConstraint()
        );

        Assert::assertInstanceOf(
            DateTimeImmutable::class,
            $user->getCreatedAt()
        );
    }

    public static function getUniqueUsernameConstraint(bool $isValid = true
    ): UniqueUsernameConstraintInterface {
        return new class($isValid) implements UniqueUsernameConstraintInterface {
            private $isValid;

            public function __construct($isValid)
            {
                $this->isValid = $isValid;
            }

            public function isUnique(string $username): bool
            {
                return $this->isValid;
            }
        };
    }
}
