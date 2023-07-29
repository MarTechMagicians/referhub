<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Referral\Entity\Referral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Referral>
 *
 * @method Referral|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referral|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referral[]    findAll()
 * @method Referral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferralRepository extends ServiceEntityRepository implements \App\Domain\Referral\ReferralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referral::class);
    }

    //    /**
    //     * @return Referral[] Returns an array of Referral objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Referral
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
