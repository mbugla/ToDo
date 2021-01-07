<?php

namespace App\Core\Application\REST\User;

use App\Core\Application\Command\CreateUser\CreateUserCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserAction
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/users", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     *
     *
     */
    public function __invoke(Request $request): Response
    {
        $requestData = json_decode($request->getContent());
        $command     = new CreateUserCommand(
            $requestData->username,
            $requestData->password
        );

        $this->handle($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
