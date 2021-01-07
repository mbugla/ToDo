<?php

namespace App\Tests\Unit\Core\Application\Command\ChangeTaskName;

use App\Core\Application\Command\ChangeTaskName\ChangeTaskNameCommand;
use App\Core\Application\Command\ChangeTaskName\ChangeTaskNameCommandHandler;
use App\Core\Domain\Model\Task\Status;
use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserFetcherInterface;
use App\Core\Infrastructure\Repository\InMemory\InMemoryTaskRepository;
use App\Tests\Unit\Core\Domain\Model\User\UserTest;
use Exception;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChangeTaskNameCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_change_task_name_if_valid()
    {
        $taskRepository = new InMemoryTaskRepository();
        $userFetcher    = $this->createMock(UserFetcherInterface::class);
        $userId         = Uuid::uuid4();
        $taskId         = Uuid::uuid4();

        $user = new User(
            $userId,
            'user',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );

        $userFetcher->expects($this->once())
            ->method('fetchRequiredUser')
            ->willReturn($user);
        $task = new Task($taskId, $userId, 'name', Status::UNDONE);
        $taskRepository->save($task);

        $command = new ChangeTaskNameCommand($taskId, 'changed name');

        $handler =
            new ChangeTaskNameCommandHandler($taskRepository, $userFetcher);

        $handler($command);

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertEquals('changed name', $task->getName());
    }

    /**
     * @test
     */
    public function it_should_not_change_task_name_if_invalid()
    {
        $taskRepository = new InMemoryTaskRepository();

        $userFetcher = $this->createMock(UserFetcherInterface::class);
        $userId      = Uuid::uuid4();
        $taskId      = Uuid::uuid4();

        $user = new User(
            $userId,
            'user',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );
        $userFetcher->expects($this->once())
            ->method('fetchRequiredUser')
            ->willReturn($user);
        $task = new Task($taskId, $userId, 'name', Status::UNDONE);
        $taskRepository->save($task);

        $command = new ChangeTaskNameCommand($taskId, 'n');

        $handler =
            new ChangeTaskNameCommandHandler($taskRepository, $userFetcher);

        try {
            $handler($command);
        } catch (Exception $e) {
            Assert::assertTrue(true);
        }

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertEquals('name', $task->getName());
    }

    /**
     * @test
     */
    public function it_should_not_change_task_name_if_no_access()
    {
        $taskRepository = new InMemoryTaskRepository();

        $userFetcher = $this->createMock(UserFetcherInterface::class);
        $userId      = Uuid::uuid4();
        $taskId      = Uuid::uuid4();

        $user = new User(
            Uuid::uuid4(),
            'user',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );
        $userFetcher->expects($this->once())
            ->method('fetchRequiredUser')
            ->willReturn($user);
        $task = new Task($taskId, $userId, 'name', Status::UNDONE);
        $taskRepository->save($task);

        $command = new ChangeTaskNameCommand($taskId, 'changed name');

        $handler =
            new ChangeTaskNameCommandHandler($taskRepository, $userFetcher);

        try {
            $handler($command);
        } catch (Exception $e) {
            Assert::assertTrue(true);
        }

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertEquals('name', $task->getName());
    }
}
