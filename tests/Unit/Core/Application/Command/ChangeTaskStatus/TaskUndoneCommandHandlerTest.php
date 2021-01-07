<?php

namespace App\Tests\Unit\Core\Application\Command\CreateTask;

use App\Core\Application\Command\ChangeTaskStatus\TaskUndoneCommand;
use App\Core\Application\Command\ChangeTaskStatus\TaskUndoneCommandHandler;
use App\Core\Domain\Model\Task\Status;
use App\Core\Domain\Model\Task\Task;
use App\Core\Infrastructure\Repository\InMemory\InMemoryTaskRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TaskUndoneCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_change_task_status_to_undone()
    {
        $taskRepository = new InMemoryTaskRepository();
        $handler        = new TaskUndoneCommandHandler($taskRepository);

        $taskId = Uuid::uuid4();
        $userId = Uuid::uuid4();
        $task   = new Task($taskId, $userId,'name', Status::DONE);
        $taskRepository->save($task);
        $command = new TaskUndoneCommand($taskId);

        $handler($command);

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertInstanceOf(Task::class, $task);
        Assert::assertEquals(Status::UNDONE, $task->getStatus());
    }
}
