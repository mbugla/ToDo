<?php
declare(strict_types=1);

namespace App\Core\Application\REST\Task;

use App\Core\Application\Command\CreateTask\CreateTaskCommand;
use App\Core\Domain\Model\User\UserFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateTaskAction
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
     * @Route("/api/tasks", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $user = $this->userFetcher->fetchRequiredUser();

        $requestData = json_decode($request->getContent());

        $command = new CreateTaskCommand($requestData->name, $user->getId());

        $id = $this->handle($command);

        $a = 1;

        return new JsonResponse(
            ['id' => $id->toString()],
            Response::HTTP_CREATED
        );
    }
}
