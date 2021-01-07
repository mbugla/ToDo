<?php

namespace App\Tests\Unit\Core\Application\Query;

use App\Core\Application\Query\GetTasksQuery;
use App\Core\Application\Query\GetTasksQueryHandler;
use App\Core\Domain\Model\Task\Status;
use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserFetcherInterface;
use App\Core\Infrastructure\Repository\InMemory\InMemoryTaskRepository;
use App\Tests\Unit\Core\Domain\Model\User\UserTest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class GetTasksQueryHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_tasks_for_user()
    {
        $taskRepository = new InMemoryTaskRepository();
        $userFetcher    = $this->createMock(UserFetcherInterface::class);
        $userId         = Uuid::uuid4();

        $user = new User(
            $userId,
            'user',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );
        $userFetcher->expects($this->once())
            ->method('fetchRequiredUser')
            ->willReturn($user);

        $task  = new Task(Uuid::uuid4(), $userId, 'name', Status::UNDONE);
        $task2 = new Task(Uuid::uuid4(), $userId, 'second task', Status::DONE);
        $task3 = new Task(
            Uuid::uuid4(), Uuid::uuid4(), 'diffrent task', Status::DONE
        );
        $taskRepository->save($task);
        $taskRepository->save($task2);
        $taskRepository->save($task3);
        $query = new GetTasksQuery($userId);

        $handler = new GetTasksQueryHandler($taskRepository, $userFetcher);

        $tasks = $handler($query);

        Assert::assertCount(2, $tasks);
    }
}
