<?php

namespace App\Core\Application\REST\Task;

use App\Core\Application\Query\GetTasksQuery;
use App\Core\Domain\Model\User\UserFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

final class GetTasksAction
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
     * @Route("/api/tasks", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Tasks resources",
     *     @OA\JsonContent(
     *       type="array",
     *         @OA\Items(
     *           @OA\Property(property="id", type="string", format="id"),
     *           @OA\Property(property="name", type="string", format="id"),
     *           @OA\Property(property="status", type="string", format="id")
     *         )
     *      )
     * )
     * @OA\Response(response=Response::HTTP_UNAUTHORIZED, description="Not authorized")
     *
     * @OA\Tag(name="Tasks")
     */
    public function __invoke(Request $request): Response
    {
        $user  = $this->userFetcher->fetchRequiredUser();
        $query = new GetTasksQuery($user->getId());

        $tasks = $this->handle($query);

        return new JsonResponse($tasks);
    }
}
