<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Enum\FixturesGroupType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class BrandsFixture extends Fixture implements FixtureGroupInterface
{
    use FixtureGroupTrait;

    private const group = [
        FixturesGroupType::DEV
    ];

    const NIKE = 'nike';
    const ADIDAS = 'adidas';

    public function load(ObjectManager $manager): void
    {
        $brand = new Brand();
        $brand->setName('Nike');

        $manager->persist($brand);

        $this->setReference(self::NIKE, $brand);

        $brand = new Brand();
        $brand->setName('Adidas');

        $this->setReference(self::ADIDAS, $brand);

        $manager->persist($brand);

        $manager->flush();
    }
}
