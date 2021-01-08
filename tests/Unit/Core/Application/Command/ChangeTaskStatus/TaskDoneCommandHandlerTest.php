<?php

namespace App\Tests\Unit\Core\Application\Command\ChangeTaskStatus;

use App\Core\Application\Command\ChangeTaskStatus\TaskDoneCommand;
use App\Core\Application\Command\ChangeTaskStatus\TaskDoneCommandHandler;
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

class TaskDoneCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_change_task_status_to_done()
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
        $handler = new TaskDoneCommandHandler($taskRepository, $userFetcher);

        $task = new Task($taskId, $userId, 'name', Status::UNDONE);
        $taskRepository->save($task);
        $command = new TaskDoneCommand($taskId);

        $handler($command);

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertInstanceOf(Task::class, $task);
        Assert::assertEquals(Status::DONE, $task->getStatus());
    }

    /**
     * @test
     */
    public function it_should_not_change_task_status_to_done_if_no_access()
    {
        $taskRepository = new InMemoryTaskRepository();
        $userFetcher    = $this->createMock(UserFetcherInterface::class);
        $userId         = Uuid::uuid4();
        $taskId         = Uuid::uuid4();

        $user = new User(
            Uuid::uuid4(),
            'user',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );
        $userFetcher->expects($this->once())
            ->method('fetchRequiredUser')
            ->willReturn($user);
        $handler = new TaskDoneCommandHandler($taskRepository, $userFetcher);

        $task = new Task($taskId, $userId, 'name', Status::UNDONE);
        $taskRepository->save($task);
        $command = new TaskDoneCommand($taskId);

        try {
            $handler($command);
        } catch (Exception $e) {
            Assert::assertTrue(true);
        }

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertInstanceOf(Task::class, $task);
        Assert::assertEquals(Status::UNDONE, $task->getStatus());
    }
}
