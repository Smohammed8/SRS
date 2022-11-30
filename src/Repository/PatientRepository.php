<?php

namespace App\Repository;

use App\Entity\Patient;
use App\Entity\Ward;
use App\Entity\Unit;
use App\Entity\Room;
use App\Entity\Bed;
use App\Entity\Admimssion;
use App\Form\AdmimssionTypeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function getQuery($search=null)
    {
        $qb= $this->createQueryBuilder('a');
        $qb->andWhere("a.fname like '%$search%'");
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

 public function total_patients()
 {
     $qb = $this->createQueryBuilder('u')
         ->select('count(u.id) as Patient')
         ->getQuery()->getSingleScalarResult();
       
         return $qb;
         
 }
/////////////////////////////////////////////////////////		
public function checkpatient($mrn_id) {

    $qb = $this->createQueryBuilder('h');
    $qb->andWhere('h.MRN = :cardNo')->setParameter('cardNo',$mrn_id);
    $count = $qb->getQuery()->getSingleScalarResult();

    if($count >0){
    return 1;	
    }
    else
    {
    return 0;	
    }
}
/////////////////////////////////////////////////////////	
    public function searchResult($searchKey)  {
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.MRN = :cardNo')->setParameter('cardNo',$searchKey);
        $qb->orWhere('h.phone = :tel')->setParameter('tel',$searchKey);
      //  $qb->orWhere('h.id = :patientID')->setParameter('patientID',$searchKey );
        return  $qb->getQuery()->getResult();
     
    }
    // /**
    //  * @return Patient[] Returns an array of Patient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Patient
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
