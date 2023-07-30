<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Referral\Entity\ReferralCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReferralCode>
 *
 * @method ReferralCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReferralCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReferralCode[]    findAll()
 * @method ReferralCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferralCodeRepository extends ServiceEntityRepository implements \App\Domain\Referral\ReferralCodeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReferralCode::class);
    }

    public function save(ReferralCode $referralCode): void
    {
        $this->getEntityManager()->persist($referralCode);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return ReferralCode[] Returns an array of ReferralCode objects
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

    //    public function findOneBySomeField($value): ?ReferralCode
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
