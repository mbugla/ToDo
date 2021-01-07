<?php
declare(strict_types=1);

namespace App\Core\Application\Command\ChangeTaskStatus;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChangeTaskStatusCommandHandler implements MessageHandlerInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(ChangeTaskStatusCommand $changeTaskStatusCommand)
    {
    }
}
