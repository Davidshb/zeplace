<?php

namespace App\Service\Login;

use App\Entity\User;
use App\Entity\UserToken;
use App\Repository\UserTokenRepository;
use App\Security\TokenHasher;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class JwtService
{
    private string $secret;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenHasher $hasher,
        private UserTokenRepository $userTokenRepository,
        ParameterBagInterface $parameterBag
    ) {
        $this->secret = $parameterBag->get('jwt')['secret'];
    }

    public function extractTokenFromHeader(string $header): string
    {
        return substr($header, 7);
    }

    public function generate(User $user): string
    {
        $now = new DateTime();
        $exp = $now->add(new DateInterval('P1D'));

        $payload = [
            'iat' => $now->getTimestamp(),
            'exp' => $exp->getTimestamp(),
            'username' => $user->getUsername()
        ];

        $token = JWT::encode($payload, $this->secret, 'HS256');

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
