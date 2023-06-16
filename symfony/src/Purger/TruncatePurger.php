<?php

namespace App\Purger;

use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class TruncatePurger implements ORMPurgerInterface
{

    private const IGNORED = [
        'user'
    ];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private EntityManagerInterface $em;

    /**
     * @inheritDoc
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function purge(): void
    {
        $connection = $this->em->getConnection();

        $tables = $connection->createSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            if(!str_contains($table, 'doctrine') && !in_array($table, self::IGNORED)) {
                $platform = $connection->getDatabasePlatform();

                $connection->beginTransaction();

                $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0;');

                $connection->executeQuery($platform->getTruncateTableSQL($table, true));

                $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1;');

            }
        }
    }
}