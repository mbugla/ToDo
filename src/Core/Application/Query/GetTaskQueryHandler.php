<?php
declare(strict_types=1);

namespace App\Core\Application\Query;

use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Core\Domain\Model\User\UserFetcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class GetTaskQueryHandler implements MessageHandlerInterface
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

    public function __invoke(GetTaskQuery $getTasksQuery): ?Task
    {
        if (!$this->userFetcher->fetchRequiredUser()->getId()->equals(
            $getTasksQuery->getUserId()
        )) {
            throw new AccessDeniedException();
        }

        return $this->taskRepository->findByUuid($getTasksQuery->getTaskId());
    }
}
