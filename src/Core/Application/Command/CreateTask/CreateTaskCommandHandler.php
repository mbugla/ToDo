<?php
declare(strict_types=1);

namespace App\Core\Application\Command\CreateTask;

use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateTaskCommandHandler implements MessageHandlerInterface
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(CreateTaskCommand $createTaskCommand): UuidInterface
    {
        $taskId = Uuid::uuid4();
        $task = new Task(
            $taskId,
            $createTaskCommand->getUserId(),
            $createTaskCommand->getName(),
            $createTaskCommand->getStatus()
        );

        $this->taskRepository->save($task);

        return $taskId;
    }
}
