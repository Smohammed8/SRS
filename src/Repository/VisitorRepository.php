<?php

namespace App\Repository;

use App\Entity\Visitor;
use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visitor[]    findAll()
 * @method Visitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visitor::class);
    }


    public function getQuery($search=null)
    {
        $qb= $this->createQueryBuilder('a');
        $qb->andWhere("a.firstName  like '%$search%'");
       // $qb ->andWhere("a.status = 0");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }


    public function getVistors()
 {
        $today = new \DateTime();
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere('a.exitTime  >= :endtime')->setParameter('endtime', $today->format("Y-m-d H:i:s"));
        $qb->andWhere('a.dateOfVisit <= :startdate')->setParameter('startdate', $today->format("Y-m-d H:i:s"));
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery()->getResult(); 
    }

    // public function  new_vistors()
    // {
    //     $today = new \DateTime();
    //     $qb = $this->createQueryBuilder('h')
    //         ->select('count(h.id) as Admimssion')
    //         ->andWhere('h.createdAt like :createdAt')->setParameter('createdAt', $today->format("Y-m-d")."%")
    //        // ->andWhere("a.status =2")
    //         ->getQuery()
    //         ->getSingleScalarResult();
          
    //         return $qb;
          
    //}


    public function getVisitors(Patient $patient,$search=null)

    {
       // $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
       // $qb->andWhere("h.status = '1'");
        $qb->andWhere('h.patient= :patient')->setParameter('patient', $patient);
       // $qb->andWhere('h.appointedAt like :appointedAt')->setParameter('appointedAt', $today->format("Y-m-d")."%");
        $qb->orderBy('h.id', 'DESC');
        return  $qb->getQuery();
    }
    // /**
    //  * @return Visitor[] Returns an array of Visitor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visitor
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
