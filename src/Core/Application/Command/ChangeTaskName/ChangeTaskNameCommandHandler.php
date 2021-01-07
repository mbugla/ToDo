<?php

namespace App\Core\Application\Command\ChangeTaskName;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChangeTaskNameCommandHandler implements MessageHandlerInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(ChangeTaskNameCommand $changeTaskNameCommand)
    {
        $task = $this->taskRepository->findByUuid(
            $changeTaskNameCommand->getTaskId()
        );

        $task->changeName($changeTaskNameCommand->getName());

        $this->taskRepository->save($task);
    }
}
