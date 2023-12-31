<?php

namespace App\DataFixtures;

use App\Entity\Sneaker;
use App\Repository\SneakerRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Filesystem;

class SneakersFixture extends Fixture
{
    public function __construct(
        private readonly string $projectDir,
        private readonly SneakerRepository $sneakerRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $adapter = new LocalAdapter("$this->projectDir/src/DataFixtures/StockxApi");
        $sneakerFs = new Filesystem($adapter);

        $yeezys = json_decode($sneakerFs->get('yeezy.json')->getContent(), true);

        foreach ($yeezys['data']['browse']['results']['edges'] as $yeezyData) {
            $uuid = $yeezyData['node']['id'] ?? null;
            if (empty($uuid) || empty($yeezyData['node']['styleId'])) {
                continue;
            }

            $yeezy = $this->sneakerRepository->findOneBy(['uuid' => $uuid]);

            if (null === $yeezy) {
                $yeezy = new Sneaker($uuid);
                $manager->persist($yeezy);
            }

            try {
                $this->createOrUpdateSneaker($yeezy, $yeezyData);
            } catch (Exception) {
                $manager->remove($yeezy);
            }
        }

        $manager->flush();
    }

    /**
     * @param array<mixed> $datas
     *
     * @throws Exception
     */
    private function createOrUpdateSneaker(Sneaker $sneaker, array $datas): void
    {
        $sneaker->setImgUrl($datas['node']['media']['imageUrl'])
                ->setThumbnailUrl($datas['node']['media']['thumbUrl'])
                ->setTitle($datas['node']['primaryTitle'])
                ->setColorisName($datas['node']['secondaryTitle'])
                ->setSku($datas['node']['styleId'])
                ->setDescription($datas['node']['description'])
                ->setStockXLink($datas['node']['urlKey'])
                ->setBrand($datas['node']['brand']);

        if (3 !== count($datas['node']['traits'])) {
            throw new Exception('not enought traits');
        }

        foreach ($datas['node']['traits'] as $trait) {
            switch ($trait['name']) {
                case 'Colorway':
                    $sneaker->setColorisCode($trait['value']);
                    break;
                case 'Retail Price':
                    $sneaker->setRetailPrice(floatval($trait['value']));
                    break;
                case 'Release Date':
                    $dropDate = $trait['value'];
                    if (empty($dropDate)) {
                        throw new Exception('drop date invalid');
                    }
                    $sneaker->setDropDate(new DateTime($dropDate));
                    break;
            }
        }
    }
}
