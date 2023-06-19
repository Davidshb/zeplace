<?php

namespace App\DataFixtures;

use App\DataFixtures\Traits\FixtureGroupTrait;
use App\Entity\Brand;
use App\Entity\Sneaker;
use App\Enum\FixturesGroupType;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SneakersFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    use FixtureGroupTrait;

    private const GROUP = [
        FixturesGroupType::DEV
    ];

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [
            BrandsFixture::class
        ];
    }

    public const AF1 = 'af1';

    public const SLIDE = 'slide';

    public function load(ObjectManager $manager): void
    {
        /** @var Brand $nike */
        $nike = $this->getReference(BrandsFixture::NIKE);
        /** @var Brand $adidas */
        $adidas = $this->getReference(BrandsFixture::NIKE);

        $sneaker = $this->createSneaker(
            $nike,
            'CW2288-111',
            'air force 1 low \'07',
            'White',
            'WHITE/WHITE',
            "Entièrement blanche, la Nike Air Force 1 Low White ‘07 présente des perforations sur le dessus et des Swoosh en relief. La broderie Nike Air au talon et la semelle blanche viennent parachever ce modèle.
La Nike Air Force 1 Low White ‘07 est sortie pour la première fois en 2007, mais comme il s'agit d'un basique, la marque la ressort régulièrement.",
            11999,
            'https://stockx.com/fr-fr/nike-air-force-1-low-white-07',
            new DateTimeImmutable('2022-04-06')
        );

        $this->setReference(self::AF1, $sneaker);

        $manager->persist($sneaker);

        $sneaker = $this->createSneaker(
            $adidas,
            'FZ5897',
            'adidas yeezy slide',
            'bone (2022 restock)',
            'BONE/BONE/BONE',
            '',
            6500,
            'https://stockx.com/fr-fr/adidas-yeezy-slide-bone-2022',
            new DateTimeImmutable('2023-04-04')
        );

        $this->setReference(self::SLIDE, $sneaker);

        $manager->persist($sneaker);

        $manager->flush();
    }

    private function createSneaker(
        Brand $brand,
        string $sku,
        string $name,
        string $colorisName,
        string $coloris,
        string $description,
        int $retailPrice,
        string $stockXLink,
        DateTimeImmutable $dropDate
    ): Sneaker {
        $sneaker = new Sneaker();
        $sneaker
            ->setBrand($brand)
            ->setSku($sku)
            ->setTitle($name)
            ->setColorisCode($coloris)
            ->setColorisName($colorisName)
            ->setDescription($description)
            ->setRetailPrice($retailPrice)
            ->setStockXLink($stockXLink)
            ->setDropDate($dropDate);
        return $sneaker;
    }
}
