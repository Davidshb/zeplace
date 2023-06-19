<?php

namespace App\Security;

use App\Enum\ApiErrorCode;
use App\Repository\UserTokenRepository;
use App\Service\Login\JwtService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param JwtService          $jwtService
     * @param TokenHasher         $hasher
     * @param UserTokenRepository $userTokenRepository
     */
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly TokenHasher $hasher,
        private readonly UserTokenRepository $userTokenRepository
    ) {
    }

    /**
     * @param Request $request
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization')
            && !empty($request->headers->get('Authorization'))
            && !str_starts_with($request->getRequestUri(), '/api');
    }


    /**
     * @param  Request $request
     * @return Passport
     * @throws NonUniqueResultException
     */
    public function authenticate(Request $request): Passport
    {
        $jwt = $this->jwtService->extractTokenFromHeader(
            $request->headers->get('Authorization')
        );

        $hashedToken = $this->hasher->hash($jwt);
        $token       = $this->userTokenRepository->findOneValid($hashedToken);

        if ($token === null) {
            throw new CustomUserMessageAuthenticationException(
                'Unauthorized',
                [
                    'errorCode' => ApiErrorCode::GENERIC_UNAUTHORIZED,
                    'httpCode'  => Response::HTTP_UNAUTHORIZED,
                ]
            );
        }

        return new SelfValidatingPassport(new UserBadge($token->getUser()->getUserIdentifier()), []);
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
        $error = new APIError();
        $error->setErrorCode($exception->getMessageData()['errorCode']);
        $error->setErrorMessage($exception->getMessage());

        $httpCode = ($exception->getMessageData()['httpCode'] ?? Response::HTTP_BAD_REQUEST);

        return new JsonResponse($error, status: $httpCode, json: false);
    }
}
