<?php
declare(strict_types=1);

namespace App\Core\Application\Command\ChangeTaskStatus;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TaskUndoneCommandHandler implements MessageHandlerInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(TaskUndoneCommand $changeTaskStatusCommand)
    {
        $task = $this->taskRepository->findByUuid($changeTaskStatusCommand->getTaskId());

        $task->markAsUndone(new DateTimeImmutable());

        $this->taskRepository->save($task);
    }
}
