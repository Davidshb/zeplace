<?php

namespace App\Security;

use App\Enum\ApiErrorCode;
use App\Repository\UserTokenRepository;
use App\Service\Helper\SerializerService;
use App\Service\Login\JwtService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    /**
     * @param JwtService $jwtService
     * @param TokenHasher $hasher
     * @param UserTokenRepository $userTokenRepository
     * @param SerializerService $serializerService
     */
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly TokenHasher $hasher,
        private readonly UserTokenRepository $userTokenRepository,
        private readonly SerializerService $serializerService,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    /**
     * @param Request $request
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') && !empty(
            $request->headers->get(
                'Authorization'
            )
        ) && !str_starts_with($request->getRequestUri(), '/api/login');
    }

    /**
     * @param Request $request
     * @return Passport
     * @throws NonUniqueResultException
     */
    public function authenticate(Request $request): Passport
    {
        $header = $request->headers->get('Authorization');

        if ($header !== null) {
            $jwt = $this->jwtService->extractTokenFromHeader($header);
            $hashedToken = $this->hasher->hash($jwt);
            $token = $this->userTokenRepository->findOneValid($hashedToken);

            if ($token !== null) {
                return new SelfValidatingPassport(new UserBadge($token->getUser()->getUserIdentifier()), []);
            }
        }

        throw new CustomUserMessageAuthenticationException(
            'accès non autorisé',
            [
                'errorCode' => ApiErrorCode::GENERIC_UNAUTHORIZED,
                'httpCode' => Response::HTTP_UNAUTHORIZED,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $error = new APIError($exception->getMessageData()['errorCode'], $exception->getMessage());

        $httpCode = $exception->getMessageData()['httpCode'] ?? Response::HTTP_BAD_REQUEST;

        return new JsonResponse($this->serializerService->toJSON($error), status: $httpCode, json: true);
    }
}
