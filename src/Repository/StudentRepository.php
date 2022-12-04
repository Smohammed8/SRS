<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Student $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Student $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }



    public function getQuery($search=null)
    {
        $qb= $this->createQueryBuilder('a');
        $qb->andWhere("a.firstName  like '%$search%'");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }


   // public function getFilter($sdate,$edate,$status=[]) {
       //$qb->andWhere('h.status   = :status')->setParameter('status',  $status['status']);
        public function getFilter($sdate,$edate,$status) {
        $qb = $this->createQueryBuilder('h');
        
        if($status==1){
           $qb->innerjoin('h.admimssions', 'ad')
          ->andWhere('ad.createdAt >= :startDate')->setParameter('startDate',  $sdate)
           ->andWhere('ad.createdAt <= :endDate')->setParameter('endDate',  $edate)
           ->andWhere('ad.isCheckedIn   = :isCheckedIn')->setParameter('isCheckedIn', false);
        }
       elseif($status==2){

            $qb->join('h.admimssions', 'ad')
           ->andWhere('ad.createdAt >= :startDate')->setParameter('startDate',  $sdate)
           ->andWhere('ad.createdAt <= :endDate')->setParameter('endDate',  $edate)
           ->andWhere('ad.isCheckedIn   = :isCheckedIn')->setParameter('isCheckedIn', true);
        }
          elseif($status==3){
           $qb->join('h.admimssions', 'ad')
           ->andWhere('ad.createdAt >= :startDate')->setParameter('startDate',  $sdate)
           ->andWhere('ad.createdAt <= :endDate')->setParameter('endDate',  $edate)
            ->andWhere('ad.dischargedAt   is not NULL');
             }
         else{

            $qb->join('h.admimssions', 'ad')
            ->andWhere('ad.createdAt >= :startDate')->setParameter('startDate',  $sdate)
            ->andWhere('ad.createdAt <= :endDate')->setParameter('endDate',  $edate);
            $qb->andWhere('ad.referOut  is not NULL');
            
           }
        
      //  return  $qb->getQuery()->getResult(); 
        return $qb->orderBy('h.id', 'ASC')->getQuery()->getResult();

       // ->andWhere("ui.fullName  LIKE '%" . $search['name'] . "%' ");
        }    

        // Check if the  patient record is existed
 public function checkIfExist($mrn,$phone=null) {
    $count = $this->createQueryBuilder('h')
    ->select('count(h.id) as Patient')
    ->andWhere('h.MRN  = :card')->setParameter('card',  $mrn)
    ->orWhere('h.phone   = :tel')->setParameter('tel',  $phone)
    ->getQuery()
    ->getSingleScalarResult(); 
    return $count;
         

}

 public function total_students()
 {
     $qb = $this->createQueryBuilder('u')
         ->select('count(u.id) as  Student')
         ->getQuery()->getSingleScalarResult();
       
         return $qb;
         
 }
    // /**
    //  * @return Student[] Returns an array of Student objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Student
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
