<?php

namespace App\Repository;

use App\Entity\AdmimssionType;
use App\Entity\Outcome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdmimssionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdmimssionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdmimssionType[]    findAll()
 * @method AdmimssionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdmimssionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdmimssionType::class);
    }




    // /**
    //  * @return AdmimssionType[] Returns an array of AdmimssionType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdmimssionType
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
