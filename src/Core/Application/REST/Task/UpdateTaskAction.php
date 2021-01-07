<?php
declare(strict_types=1);

namespace App\Core\Application\REST\Task;

use App\Core\Application\Command\ChangeTaskName\ChangeTaskNameCommand;
use App\Core\Application\Command\ChangeTaskStatus\TaskDoneCommand;
use App\Core\Application\Command\ChangeTaskStatus\TaskUndoneCommand;
use App\Core\Domain\Model\Task\Status;
use App\Core\Domain\Model\User\UserFetcherInterface;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UpdateTaskAction
{
    use HandleTrait;

    private UserFetcherInterface $userFetcher;

    /**
     * CreateTaskAction constructor.
     *
     * @param MessageBusInterface  $messageBus
     * @param UserFetcherInterface $userFetcher
     */
    public function __construct(
        MessageBusInterface $messageBus,
        UserFetcherInterface $userFetcher
    ) {
        $this->messageBus  = $messageBus;
        $this->userFetcher = $userFetcher;
    }

    /**
     * @Route("/api/tasks/{taskId}", methods={"PUT"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $requestData = json_decode($request->getContent());

        $taskId = Uuid::fromString($requestData->taskId);

        if(isset($requestData->status)) {
            $this->handleStatus($requestData, $taskId);
        }

        if(isset($requestData->name)) {
            $command = new ChangeTaskNameCommand($taskId, $requestData->name);
            $this->handle($command);
        }

        return new JsonResponse('', Response::HTTP_ACCEPTED);
    }

    /**
     * @param                            $requestData
     * @param \Ramsey\Uuid\UuidInterface $taskId
     */
    protected function handleStatus(
        $requestData,
        \Ramsey\Uuid\UuidInterface $taskId
    ): void {
        switch ($requestData->status) {
            case Status::DONE:
                $command = new TaskDoneCommand($taskId);
                break;
            case Status::UNDONE:
                $command = new TaskUndoneCommand($taskId);
                break;
            default:
                throw new InvalidArgumentException("Status not supported");
        }

        $this->handle($command);
    }
}
