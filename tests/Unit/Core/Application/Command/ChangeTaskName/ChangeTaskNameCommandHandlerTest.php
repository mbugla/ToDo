<?php

namespace App\Tests\Unit\Core\Application\Command\ChangeTaskName;

use App\Core\Application\Command\ChangeTaskName\ChangeTaskNameCommand;
use App\Core\Application\Command\ChangeTaskName\ChangeTaskNameCommandHandler;
use App\Core\Domain\Model\Task\Status;
use App\Core\Domain\Model\Task\Task;
use App\Core\Infrastructure\Repository\InMemory\InMemoryTaskRepository;
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

        $taskId = Uuid::uuid4();
        $userId = Uuid::uuid4();
        $task   = new Task($taskId, $userId,'name', Status::UNDONE);
        $taskRepository->save($task);

        $command = new ChangeTaskNameCommand($taskId, 'changed name');

        $handler = new ChangeTaskNameCommandHandler();

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

        $taskId = Uuid::uuid4();
        $userId = Uuid::uuid4();
        $task   = new Task($taskId, $userId,'name', Status::UNDONE);
        $taskRepository->save($task);

        $command = new ChangeTaskNameCommand($taskId, 'n');

        $handler = new ChangeTaskNameCommandHandler();

        $handler($command);

        $task = $taskRepository->findByUuid($taskId);

        Assert::assertEquals('name', $task->getName());
    }
}
