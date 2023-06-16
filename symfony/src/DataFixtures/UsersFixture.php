<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\FixturesGroupType;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixture extends Fixture implements FixtureGroupInterface
{
    use FixtureGroupTrait;

    private const group = [
        FixturesGroupType::DEV
    ];

    public const USER = 'user';

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);

        $user = $userRepository->findOneBy(['username' => 'davidshbo']);

        if ($user === null) {
            $user = $this->createUser(
                'davidshbo',
                '123456',
                'tonydavidseh@hotmail.fr',
                true
            );
            $manager->persist($user);
        }

        $this->setReference(self::USER, $user);

        $user = $userRepository->findOneBy(['username' => 'vendeur1']);

        if ($user === null) {
            $user = $this->createUser(
                'vendeur1',
                'motdepasse',
                'vendeur1@hotmail.fr'
            );
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createUser(
        string $username,
        string $plainPassword,
        string $email,
        bool   $isVerified = false
    ): User
    {
        $user = new User();
        $user->setUsername($username)
            ->setEmail($email)
            ->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword))
            ->setIsVerified($isVerified);

        return $user;
    }
}
