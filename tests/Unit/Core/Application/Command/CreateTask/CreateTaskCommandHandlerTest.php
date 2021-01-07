<?php

namespace App\Tests\Unit\Core\Application\Command\CreateTask;

use App\Core\Application\Command\CreateTask\CreateTaskCommand;
use App\Core\Application\Command\CreateTask\CreateTaskCommandHandler;
use App\Core\Domain\Model\Task\Task;
use App\Core\Infrastructure\Repository\InMemory\InMemoryTaskRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateTaskCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_a_new_task()
    {
        $taskRepository = new InMemoryTaskRepository();
        $handler        = new CreateTaskCommandHandler($taskRepository);

        $userId    = Uuid::uuid4();
        $command = new CreateTaskCommand('task', $userId);

        $handler($command);

        $userTasks = $taskRepository->findByUser($userId);
        $task  = array_shift($userTasks);

        Assert::assertInstanceOf(Task::class, $task);
        Assert::assertEquals('task', $task->getName());

    }
}
