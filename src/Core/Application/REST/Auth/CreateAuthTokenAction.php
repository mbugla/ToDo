<?php
declare(strict_types=1);

namespace App\Core\Application\REST\Auth;

use App\Core\Application\Command\CreateAuthToken\CreateAuthTokenCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CreateAuthTokenAction
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/auth-token", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $requestData = json_decode($request->getContent());

        $token = $this->handle(new CreateAuthTokenCommand(
            $requestData->username,
            $requestData->password
        ));

        return new JsonResponse(['token' => $token], Response::HTTP_CREATED);
    }
}
