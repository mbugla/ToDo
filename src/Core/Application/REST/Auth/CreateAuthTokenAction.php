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
use OpenApi\Annotations as OA;

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
     *     description="Auth token created",
     *     @OA\Schema(@OA\Property(property="token", type="string"))
     * )
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description="Missing parameters")
     *
     * @OA\Tag(name="Auth token")
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

        $token = $this->handle(
            new CreateAuthTokenCommand(
                $requestData->username,
                $requestData->password
            )
        );

        return new JsonResponse(['token' => $token], Response::HTTP_CREATED);
    }
}
