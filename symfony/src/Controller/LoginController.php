<?php

namespace App\Controller;

use App\Dto\Input\LoginDTO;
use App\Dto\Output\TokenDTO;
use App\Enum\ApiErrorCode;
use App\Exception\MultipleApiError;
use App\Repository\UserRepository;
use App\Repository\UserTokenRepository;
use App\Security\APIError;
use App\Security\TokenHasher;
use App\Service\Hydrator\DTOHydrator;
use App\Service\Login\JwtService;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Login')]
#[Route('/api/login', name: 'api_')]
class LoginController extends AbstractApiController
{
    /**
     * @throws Exception
     */
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
    #[Route('', name: 'login', methods: ['POST'])]
    public function login(
        Request $request,
        UserPasswordHasherInterface $hasher,
        JwtService $jwtService,
        UserRepository $userRepository,
        DTOHydrator $DTOHydrator
    ): JsonResponse {
        try {
            $loginDTO = $DTOHydrator->hydrateFromJson($request->getContent(), LoginDTO::class);
        } catch (MultipleApiError $e) {
            return $this->getError($e->getApiErrors(), Response::HTTP_BAD_REQUEST);
        } catch (Exception) {
            return $this->getBadRequest();
        }

        $user = $userRepository->findOneBy(['username' => $loginDTO->username]);

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

    /**
     * @throws Exception
     */
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'username')]
        )
    )]
    #[Route(
        '/fake',
        name: 'login_fake',
        methods: 'GET',
        condition: "'%kernel.environment%' === 'dev'"
    )]
    public function fake(
        Request $request,
        UserRepository $userRepository,
        JwtService $jwtService
    ): Response {
        $user = $userRepository->findOneBy(['username' => $request->query->get('u')]);

        if ($user === null) {
            return $this->getNotFoundError(
                ApiErrorCode::LOGIN_USER_NOT_FOUND,
                'les identifiants sont inconnus'
            );
        }

        return new Response($jwtService->generate($user));
    }

    #[Route('/logout', name: 'logout', methods: 'GET')]
    public function logout(
        Request $request,
        UserTokenRepository $userTokenRepository,
        JwtService $jwtService,
        TokenHasher $hasher
    ): JsonResponse {
        $header = $request->headers->get('Authorization');

        if (!empty($header)) {
            $token = $jwtService->extractTokenFromHeader($header);
            $userTokenRepository->removeToken($hasher->hash($token));
        }

        return new JsonResponse();
    }
}
