<?php

namespace App\Controller;

use App\Dto\Input\LoginDTO;
use App\Dto\Output\TokenDTO;
use App\Enum\ApiErrorCode;
use App\Exception\MultipleApiError;
use App\Security\APIError;
use App\Service\Hydrator\LoginHydrator;
use App\Service\Login\JwtService;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractApiController
{
    #[OA\Tag(name: 'Login')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: LoginDTO::class),
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Connexion réussie',
        content: new OA\JsonContent(
            ref: new Model(type: TokenDTO::class),
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'requête non conforme',
        content: new OA\JsonContent(
            examples: [
                ApiErrorCode::LOGIN_USERNAME_INVALID->name => new OA\Examples(
                    example: "le nom d'utilisateur n'est pas conforme",
                    summary: '',
                    value: [
                        'errorCode' => ApiErrorCode::LOGIN_USERNAME_INVALID->name,
                        'errorMessage' => ''
                    ]
                ),
                ApiErrorCode::LOGIN_PASSWORD_INVALID->name => new OA\Examples(
                    example: "le mot de passe n'est pas conforme",
                    summary: '',
                    value: [
                        'errorCode' => ApiErrorCode::LOGIN_PASSWORD_INVALID->name,
                        'errorMessage' => ''
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'utilisateur introuvable',
        content: new OA\JsonContent(
            ref: new Model(type: APIError::class),
            type: 'object'
        )
    )]
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        LoginHydrator $loginHydrator,
        UserService $userService,
        UserPasswordHasherInterface $hasher,
        JwtService $jwtService
    ): JsonResponse {
        try {
            $loginDTO = $loginHydrator->hydrateLoginDTO($request->getContent());
        } catch (MultipleApiError $e) {
            return $this->getError($e->getApiErrors(), Response::HTTP_BAD_REQUEST);
        }

        $user = $userService->getUserFromLogin($loginDTO);

        if ($user === null) {
            return $this->getNotFoundError(
                ApiErrorCode::LOGIN_USER_NOT_FOUND,
                'les identifiants sont inconnus'
            );
        }

        if (!$hasher->isPasswordValid($user, $loginDTO->password)) {
            return $this->getBadRequest(
                ApiErrorCode::LOGIN_PASSWORD_INVALID,
                "les identifiants sont inconnus"
            );
        }

        $token = $jwtService->generate($user);

        $dto = new TokenDTO($token);

        return new JsonResponse($this->serializerService->toJSON($dto), json: true);
    }
}
