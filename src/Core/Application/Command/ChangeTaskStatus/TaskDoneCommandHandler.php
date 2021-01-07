<?php
declare(strict_types=1);

namespace App\Core\Application\Command\ChangeTaskStatus;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Core\Domain\Model\User\UserFetcherInterface;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TaskDoneCommandHandler implements MessageHandlerInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * @var UserFetcherInterface
     */
    private UserFetcherInterface $userFetcher;

    /**
     * TaskDoneCommandHandler constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     * @param UserFetcherInterface    $userFetcher
     */
    public function __construct(TaskRepositoryInterface $taskRepository, UserFetcherInterface $userFetcher)
    {
        $this->taskRepository = $taskRepository;
        $this->userFetcher = $userFetcher;
    }

    public function __invoke(TaskDoneCommand $changeTaskStatusCommand)
    {
        $task = $this->taskRepository->findByUuid($changeTaskStatusCommand->getTaskId());

        if(!$task) {
            throw new InvalidArgumentException("Task not found");
        }

        if(!$this->userFetcher->fetchRequiredUser()->getId()->equals($task->getUserId())) {
            throw new AccessDeniedException();
        }

        $task->markAsDone(new DateTimeImmutable());

        $this->taskRepository->save($task);
    }
}
