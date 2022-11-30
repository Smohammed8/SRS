<?php

namespace App\Repository;

use App\Entity\Unit;
use App\Entity\bed;
use App\Entity\Ward;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Unit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unit[]    findAll()
 * @method Unit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unit::class);
    }

    
    public function getUnitsAsArray(Ward $ward)
    {
        $qb = $this->createQueryBuilder('h');
        $qb->andWhere('h.ward= :ward')->setParameter('ward', $ward);
        $qb->orderBy('h.name', 'ASc');
        return  $qb->getQuery();
    }


    public function getQuery($search = null)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere("a.name like '%$search%'");
        // $qb ->andWhere("a.status = 0");
        $qb->orderBy('a.id', 'DESC');
        return  $qb->getQuery();
    }
//////////////////////////////////////////////////////////////////////////////
   public function units_in_medical_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
           ->andWhere("a.ward = 1")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

    public function units_in_surgical_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 2")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function units_in_pediatrics_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 5")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

    public function units_in_mathernity_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 9")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function units_in_gyne_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 5")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }


    public function units_in_orthopedics_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 11")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function units_in_nicu_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 10")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;        
    }


    public function units_in_ophtha_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 8")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;        
    }

    public function units_in_psychiatric_ward()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->andWhere("a.ward = 7")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;        
    }

 ////////////////////////////////////////////////////////////////////////
    public function total_units()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Unit')
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function getUnits_in_ward(Ward $ward, $search = null)
    {
        $qb = $this->createQueryBuilder('h');
        $qb->andWhere('h.ward= :ward')->setParameter('ward', $ward);
        $qb->andWhere("h.name like '%$search%'");
        $qb->orderBy('h.name', 'ASc');
        return  $qb->getQuery();
    }


    // /**
    //  * @return Unit[] Returns an array of Unit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Unit
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
