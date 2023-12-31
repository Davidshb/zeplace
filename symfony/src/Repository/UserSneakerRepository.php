<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserSneaker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserSneaker>
 *
 * @method UserSneaker|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSneaker|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSneaker[]    findAll()
 * @method UserSneaker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSneakerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSneaker::class);
    }

    /**
     * @param User $user
     * @return array<UserSneaker>
     */
    public function findSoldByUser(User $user): array
    {
        $qb = $this->createQueryBuilder('us')
            ->addSelect('s')
            ->join('us.sneaker', 's')
            ->where('us.user = :user')
            ->andWhere('us.sellingDate IS NOT NULL')
            ->orderBy('us.updatedAt', 'DESC');

        $qb->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return array<UserSneaker>
     */
    public function findSaleByUser(User $user): array
    {
        $qb = $this->createQueryBuilder('us')
            ->addSelect('s')
            ->innerJoin('us.sneaker', 's')
            ->where('us.sellingDate IS NULL')
            ->andWhere('us.user = :user')
            ->orderBy('us.updatedAt', 'DESC');

        $qb->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }
}
