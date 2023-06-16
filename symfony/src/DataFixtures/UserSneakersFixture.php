<?php

namespace App\DataFixtures;

use App\Entity\Sneaker;
use App\Entity\User;
use App\Entity\UserSneaker;
use App\Enum\FixturesGroupType;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserSneakersFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    use FixtureGroupTrait;

    private const group = [
        FixturesGroupType::DEV
    ];

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UsersFixture::USER);

        /** @var Sneaker $af1 */
        $af1 = $this->getReference(SneakersFixture::AF1);

        /** @var Sneaker $slide */
        $slide = $this->getReference(SneakersFixture::SLIDE);

        $userSneaker = $this->createUserSneaker(
            $user,
            $af1,
            12099,
            13500,
            new DateTime('01-01-2023'),
            0
        );

        $manager->persist($userSneaker);

        $userSneaker = $this->createUserSneaker(
            $user,
            $slide,
            6600,
            10000,
            new DateTime('05-01-2023'),
            10
        );

        $manager->persist($userSneaker);

        $manager->flush();
    }

    private function createUserSneaker(
        User              $user,
        Sneaker           $sneaker,
        int               $sellingPrice,
        int $purchasePrice,
        DateTimeInterface $dateTime,
        int               $shippingCost
    ): UserSneaker
    {
        $userSneaker = new UserSneaker();

        $userSneaker->setUser($user)
            ->setSneaker($sneaker)
            ->setSellingPrice($sellingPrice)
            ->setPurchasePrice($purchasePrice)
            ->setPurchaseDate($dateTime)
            ->setShippingCost($shippingCost);

        return $userSneaker;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [
            UsersFixture::class,
            SneakersFixture::class
        ];
    }
}
