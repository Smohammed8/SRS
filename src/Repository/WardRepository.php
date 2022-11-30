<?php

namespace App\Repository;

use App\Entity\Ward;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ward|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ward|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ward[]    findAll()
 * @method Ward[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ward::class);
    }

    public function getQuery($search=null)
    {
        $qb= $this->createQueryBuilder('a');
        $qb->andWhere("a.name like '%$search%'");
       // $qb ->andWhere("a.status = 0");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }


    public function getWardsInEmrg()
    {
        $qb= $this->createQueryBuilder('a');
         $qb ->andWhere("a.id = 1");
         $qb ->orWhere("a.id = 2");
         $qb ->orWhere("a.id = 11");

        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery()->getResult();
    }


    public function getWardsInPeda()
    {
        $qb= $this->createQueryBuilder('a');
         $qb ->andWhere("a.id = 4");
         $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery()->getResult();
    }

    public function getWardsInOPtha()
    {
        $qb= $this->createQueryBuilder('a');
         $qb ->andWhere("a.id = 8");
         $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery()->getResult();
    }



    public function getWardsAsArray()
    {
        $qb= $this->createQueryBuilder('a');
        return $qb->getQuery();
    }

    public function total_wards()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Ward')
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

    // /**
    //  * @return Ward[] Returns an array of Ward objects
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
    public function findOneBySomeField($value): ?Ward
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
