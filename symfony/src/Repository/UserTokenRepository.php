<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserToken[] findAll()
 * @method UserToken[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserToken::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneValid(string $token): ?UserToken
    {
        $qb = $this->createQueryBuilder('ut')
            ->where('ut.token = :token')
            ->andWhere('ut.expireAt > CURRENT_DATE()');

        $qb->setParameter('token', $token);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function purgeTokenForUser(User $user): void
    {
        $qb = $this->createQueryBuilder('ut')
            ->delete()
            ->where('ut.expireAt < CURRENT_DATE()')
            ->andWhere('ut.user = :user');

        $qb->setParameter('user', $user);

        $qb->getQuery()->execute();
    }

    public function removeToken(string $token): void
    {
        $qb = $this->createQueryBuilder('ut')
            ->delete()
            ->where('ut.token = :token');

        $qb->setParameter('token', $token);

        $qb->getQuery()->execute();
    }
}
