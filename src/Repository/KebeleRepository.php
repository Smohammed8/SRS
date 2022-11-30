<?php

namespace App\Repository;

use App\Entity\Kebele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Kebele|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kebele|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kebele[]    findAll()
 * @method Kebele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KebeleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kebele::class);
    }

    // /**
    //  * @return Kebele[] Returns an array of Kebele objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kebele
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
