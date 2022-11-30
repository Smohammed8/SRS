<?php

namespace App\Repository;

use App\Entity\Woreda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Woreda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Woreda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Woreda[]    findAll()
 * @method Woreda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WoredaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Woreda::class);
    }

    // /**
    //  * @return Woreda[] Returns an array of Woreda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Woreda
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
