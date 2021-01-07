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
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

final class UpdateTaskAction
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
     * @Route("/api/tasks/{taskId}", methods={"PATCH"})
     *
     * @param string  $taskId
     * @param Request $request
     *
     * @return Response
     *
     * @OA\RequestBody(
     *     description="Json payload",
     *     required=true,
     *     @OA\JsonContent(
     *       example="{name: task name}",
     *       @OA\Property(property="name", type="string", format="name", example="first task"),
     *       @OA\Property(property="status", type="string", format="status", example="done"),
     *     )
     * )
     * @OA\Response(
     *     response=Response::HTTP_ACCEPTED,
     *     description="Task updated"
     * )
     * @OA\Response(response=Response::HTTP_UNAUTHORIZED, description="Not authorized")
     *
     * @OA\Tag(name="Tasks")
     */
    public function __invoke(string $taskId, Request $request): Response
    {
        $requestData = json_decode($request->getContent());

        $taskId = Uuid::fromString($taskId);

        if (isset($requestData->status)) {
            $this->handleStatus($requestData, $taskId);
        }

        if (isset($requestData->name)) {
            $command = new ChangeTaskNameCommand($taskId, $requestData->name);
            $this->handle($command);
        }

        return new JsonResponse('', Response::HTTP_ACCEPTED);
    }

    /**
     * @param                            $requestData
     * @param UuidInterface              $taskId
     */
    protected function handleStatus($requestData, UuidInterface $taskId): void
    {
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
