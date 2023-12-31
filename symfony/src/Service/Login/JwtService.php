<?php

namespace App\Service\Login;

use App\Entity\User;
use App\Entity\UserToken;
use App\Repository\UserTokenRepository;
use App\Security\TokenHasher;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class JwtService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenHasher $hasher,
        private UserTokenRepository $userTokenRepository,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function extractTokenFromHeader(string $header): string
    {
        return substr($header, 7);
    }

    /**
     * @throws Exception
     */
    public function generate(User $user): string
    {
        $now = new DateTime();
        $exp = $now->add(new DateInterval($this->parameterBag->get('token.duration')));

        $payload = [
            'iat' => $now->getTimestamp(),
            'exp' => $exp->getTimestamp(),
            'username' => $user->getUsername()
        ];

        $token = JWT::encode($payload, $this->parameterBag->get('jwt.secret'), 'HS256');

        $userToken = new UserToken();
        $userToken->setUser($user);
        $userToken->setToken($this->hasher->hash($token));
        $userToken->setExpireAt($exp);

        $this->userTokenRepository->purgeTokenForUser($user);
        $this->entityManager->persist($userToken);
        $this->entityManager->flush();

        return $token;
    }
}
