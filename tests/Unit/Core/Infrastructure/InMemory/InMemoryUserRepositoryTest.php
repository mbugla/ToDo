<?php

namespace App\Tests\Unit\Core\Infrastructure\InMemory;

use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserRepositoryInterface;
use App\Core\Infrastructure\Repository\InMemory\InMemoryUserRepository;
use App\Tests\Unit\Core\Domain\Model\User\UserTest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InMemoryUserRepositoryTest extends TestCase
{
    private static User $user;

    /**
     * @test
     */
    public function it_is_able_to_store_users()
    {
        $repo = new InMemoryUserRepository();

        $repo->add(self::$user);

        Assert::assertTrue(true);

        return $repo;
    }

    /**
     * @test
     * @depends it_is_able_to_store_users
     *
     * @param UserRepositoryInterface $repository
     */
    public function it_is_able_to_find_user_by_id(
        UserRepositoryInterface $repository
    ) {
        $user = $repository->findByUuid(self::$user->getId());

        Assert::assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     * @depends it_is_able_to_store_users
     *
     * @param UserRepositoryInterface $repository
     */
    public function it_is_able_to_find_user_by_username(
        UserRepositoryInterface $repository
    ) {
        $user = $repository->findByUsername('john');

        Assert::assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     * @depends it_is_able_to_store_users
     *
     * @param UserRepositoryInterface $repository
     */
    public function it_is_able_to_remove_user(
        UserRepositoryInterface $repository
    ) {
        $repository->remove(self::$user);

        Assert::assertNull($repository->findByUuid(self::$user->getId()));
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$user = $user = new User(
            Uuid::uuid4(),
            'john',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );
    }
}
