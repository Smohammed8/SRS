<?php

namespace App\Repository;

use App\Entity\Bed;
use App\Entity\Room;
use App\Entity\Unit;
use App\Entity\Ward;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bed|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bed|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bed[]    findAll()
 * @method Bed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bed::class);
    }


    public function getQuery($search = null)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere("a.name like '%$search%'");
        // $qb ->andWhere("a.status = 0");
        $qb->orderBy('a.id', 'ASC');
        return  $qb->getQuery();
    }

    public function total_beds()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u.id) as Beds')
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

    // public function getAdmited($ward) {
        
    //     return $this->createQueryBuilder('a')
    //     ->join('a.bed', 'b')
    //     ->join('b.room','r')
    //     ->join('r.unit','u')
    //     ->andWhere("a.status = 0")
    //     ->andWhere('u.ward =:ward')->setParameter('ward',  $ward)
    //     ->andWhere("a.isCheckedIn = 1")
    //     ->orderBy('a.id', 'DESC')
    //     ->getQuery(); 
    // }
//////////////////////////////////////////////////////////


public function beds_in_medical_ward()
{
    $qb = $this->createQueryBuilder('a')
         ->join('a.room','b')
         ->join('b.unit','c')
        ->select('count(a.id) as Bed')
        ->andWhere('c.ward =:ward')->setParameter('ward', 1)
        ->getQuery()
        ->getSingleScalarResult();
      
        return $qb;
        
}

public function beds_in_surgical_ward()
{
    $qb = $this->createQueryBuilder('a')
    ->join('a.room','b')
    ->join('b.unit','c')
   ->select('count(a.id) as Bed')
   ->andWhere('c.ward =:ward')->setParameter('ward', 2)
   ->getQuery()
   ->getSingleScalarResult();
 
   return $qb;
        
}
public function beds_in_pediatrics_ward()
{
    $qb = $this->createQueryBuilder('a')
    ->join('a.room','b')
    ->join('b.unit','c')
   ->select('count(a.id) as Bed')
   ->andWhere('c.ward =:ward')->setParameter('ward', 4)
   ->getQuery()
   ->getSingleScalarResult();
 
   return $qb;
        
}

public function beds_in_mathernity_ward()
{
    $qb = $this->createQueryBuilder('a')
         ->join('a.room','b')
         ->join('b.unit','c')
        ->select('count(a.id) as Bed')
        ->andWhere('c.ward =:ward')->setParameter('ward', 9)
        ->getQuery()
        ->getSingleScalarResult();
      
        return $qb;
        
}
public function beds_in_gyne_ward()
{
    $qb = $this->createQueryBuilder('a')
    ->join('a.room','b')
    ->join('b.unit','c')
   ->select('count(a.id) as Bed')
   ->andWhere('c.ward =:ward')->setParameter('ward', 5)
   ->getQuery()
   ->getSingleScalarResult();
 
   return $qb;
}


    public function beds_in_orthopedics_ward(){
    $qb = $this->createQueryBuilder('a')
    ->join('a.room','b')
    ->join('b.unit','c')
    ->select('count(a.id) as Bed')
    ->andWhere('c.ward =:ward')->setParameter('ward', 11)
    ->getQuery()
    ->getSingleScalarResult();
 
   return $qb;
}


public function beds_in_nicu_ward(){
    $qb = $this->createQueryBuilder('a')
    ->join('a.room','b')
    ->join('b.unit','c')
    ->select('count(a.id) as Bed')
    ->andWhere('c.ward =:ward')->setParameter('ward', 10)
    ->getQuery()
    ->getSingleScalarResult();
 
   return $qb;
}


public function beds_in_ophtha_ward(){
    $qb = $this->createQueryBuilder('a')
    ->join('a.room','b')
    ->join('b.unit','c')
    ->select('count(a.id) as Bed')
    ->andWhere('c.ward =:ward')->setParameter('ward', 8)
    ->getQuery()
    ->getSingleScalarResult();
 
   return $qb;
}

public function beds_in_psychiatric_ward(){
    $qb = $this->createQueryBuilder('a')
    ->join('a.room','b')
    ->join('b.unit','c')
    ->select('count(a.id) as Bed')
    ->andWhere('c.ward =:ward')->setParameter('ward', 7)
    ->getQuery()
    ->getSingleScalarResult();
 
   return $qb;
}

//////////////////////////////////////////////////////////



    public function free_beds()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u.id) as Bed')
            ->andWhere("u.accessibility =0")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function getBeds(Room $room, $search = null)
    {
        $qb = $this->createQueryBuilder('h');
        $qb->andWhere('h.room= :room')->setParameter('room', $room);
       // $qb->andWhere("h.name like '%$search%'");
        $qb->orderBy('h.name', 'ASc');
        return  $qb->getQuery();
    }



    public function getFreeBeds(Room $room, $search = null)
    {
        $qb = $this->createQueryBuilder('h');

        $qb->andWhere('h.room= :room')->setParameter('room', $room);
        $qb->andWhere("h.accessibility = 0");
        $qb->andWhere("h.isFunctional = 1");
        $qb->orderBy('h.id', 'ASC');
        return  $qb->getQuery();
    }
    // /**
    //  * @return Bed[] Returns an array of Bed objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bed
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
