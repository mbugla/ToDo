<?php

namespace App\Tests\Unit\Core\Application\Command\CreateTask;

use App\Core\Application\Command\ChangeTaskStatus\ChangeTaskStatusCommand;
use App\Core\Application\Command\ChangeTaskStatus\ChangeTaskStatusCommandHandler;
use App\Core\Application\Command\CreateTask\CreateTaskCommand;
use App\Core\Application\Command\CreateTask\CreateTaskCommandHandler;
use App\Core\Domain\Model\Task\Status;
use App\Core\Domain\Model\Task\Task;
use App\Core\Infrastructure\Repository\InMemory\InMemoryTaskRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChangeTaskStatusCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_change_task_status()
    {
        $taskRepository = new InMemoryTaskRepository();
        $handler        = new ChangeTaskStatusCommandHandler($taskRepository);

        $taskId = Uuid::uuid4();
        $userId = Uuid::uuid4();
        $task   = new Task($taskId, 'name', $userId, Status::UNDONE);
        $taskRepository->save($task);
        $command = new ChangeTaskStatusCommand($taskId, Status::DONE);

        $handler($command);

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertInstanceOf(Task::class, $task);
        Assert::assertEquals(Status::DONE, $task->getStatus());
    }
}
