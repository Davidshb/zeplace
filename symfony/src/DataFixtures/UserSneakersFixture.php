<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\RandomFactory;
use App\Entity\Sneaker;
use App\Entity\User;
use App\Entity\UserSneaker;
use App\Enum\ShoeSize;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class UserSneakersFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UsersFixture::USER, User::class);

        /** @var array<Sneaker> $sneakers */
        $sneakers = $manager->getRepository(Sneaker::class)->createQueryBuilder('s')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();

        $today = new DateTime('today');
        $sizeShoes = ShoeSize::cases();
        $sizeShoesCount = count($sizeShoes) - 1;

        foreach ($sneakers as $sneaker) {
            $res = new UserSneaker($user, $sneaker);
            $diff = $today->diff($sneaker->getDropDate());
            $diff = new DateInterval("P" . rand(0, $diff->d) . "D");
            $retailPrice = $sneaker->getRetailPrice();
            $res->setPurchasePrice($retailPrice)
                ->setShippingCost(RandomFactory::getRandomFloat(0, 20))
                ->setPurchaseDate($sneaker->getDropDate()->add($diff))
                ->setSize($sizeShoes[rand(0, $sizeShoesCount)]);

            if (rand(0, 1) === 0) {
                $res->setSellingPrice(RandomFactory::getRandomFloat($retailPrice, $retailPrice + 50));

                if (rand(0, 1) === 0) {
                    $res->setSellingDate($res->getPurchaseDate()->add(new DateInterval('P5D')))
                        ->setComment(RandomFactory::getLoremIpsum());
                }
            }

            $manager->persist($res);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UsersFixture::class,
            SneakersFixture::class
        ];
    }
}
