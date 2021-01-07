<?php

namespace App\Core\Application\REST\User;

use App\Core\Application\Command\CreateUser\CreateUserCommand;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

final class CreateUserAction
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
     * @OA\RequestBody(
     *     description="Json payload",
     *     required=true,
     *     @OA\JsonContent(
     *       required={"username","password"},
     *       example="{username: username, password: pass}",
     *       @OA\Property(property="username", type="string", format="username", example="john"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345")
     *     )
     * )
     * @OA\Response(
     *     response=Response::HTTP_CREATED,
     *     description="User created",
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="Missing parameters")
     *
     * @OA\Tag(name="Users")
     */
    public function __invoke(Request $request): Response
    {
        $requestData = json_decode($request->getContent());
        if (!isset($requestData->username, $requestData->password)) {
            return new JsonResponse(
                ['error' => 'Provide username and password in request body'],
                Response::HTTP_BAD_REQUEST
            );
        }
        $command     = new CreateUserCommand(
            $requestData->username,
            $requestData->password
        );

        try {
            $this->handle($command);
        } catch (Exception $e) {
            return new JsonResponse(
                ['message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
