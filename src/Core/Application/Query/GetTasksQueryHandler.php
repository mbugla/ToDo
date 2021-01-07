<?php
declare(strict_types=1);

namespace App\Core\Application\Query;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetTasksQueryHandler implements MessageHandlerInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * GetTasksQueryHandler constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(GetTasksQuery $getTasksQuery): array
    {
        return $this->taskRepository->findByUser($getTasksQuery->getUserId());
    }
}
