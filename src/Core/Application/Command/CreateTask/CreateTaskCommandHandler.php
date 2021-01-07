<?php
declare(strict_types=1);

namespace App\Core\Application\Command\CreateTask;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateTaskCommandHandler implements MessageHandlerInterface
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(CreateTaskCommand $createTaskCommand)
    {
    }
}
