<?php

namespace App\Core\Application\Command\ChangeTaskName;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Core\Domain\Model\User\UserFetcherInterface;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ChangeTaskNameCommandHandler implements MessageHandlerInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * @var UserFetcherInterface
     */
    private UserFetcherInterface $userFetcher;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        UserFetcherInterface $userFetcher
    ) {
        $this->taskRepository = $taskRepository;
        $this->userFetcher    = $userFetcher;
    }

    public function __invoke(ChangeTaskNameCommand $changeTaskNameCommand)
    {
        $task = $this->taskRepository->findByUuid(
            $changeTaskNameCommand->getTaskId()
        );

        if (!$task) {
            throw new InvalidArgumentException("Task not found");
        }

        if (!$this->userFetcher->fetchRequiredUser()->getId()->equals(
            $task->getUserId()
        )) {
            throw new AccessDeniedException();
        }

        $task->changeName(
            $changeTaskNameCommand->getName(),
            new DateTimeImmutable()
        );

        $this->taskRepository->save($task);
    }
}
