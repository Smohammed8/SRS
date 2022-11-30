<?php

namespace App\Repository;

use App\Entity\Admimssion;
use App\Entity\Patient;
use App\Entity\Ward;
use App\Entity\Unit;
use App\Entity\Room;
use App\Entity\Bed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Admimssion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admimssion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admimssion[]    findAll()
 * @method Admimssion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdmimssionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admimssion::class);
    }

    public function getAdmissionBaseOnWard(Ward $ward)
    {
        
        $qb= $this->createQueryBuilder('a');
        $qb->where('a.bed = :bed')->setParameter('bed',$$ard);
        $qb->getQuery()->getResult();
    }

    public function getQuery($search=null)
    {
        $qb= $this->createQueryBuilder('a');
       // $qb->andWhere("a.isCheckedIn =1");
        $qb->andWhere("a.id like '%$search%'");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }
  
    public function getWaitingQuery()
    {
         $qb= $this->createQueryBuilder('a');
         $qb->andWhere("a.isCheckedIn =0");
         $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }
//////////////////////////////
    public function total_admimssions()
    {
            $qb = $this->createQueryBuilder('u')
            ->select('count(u.id) as Admimssion')
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

    public function totalAdmimssions($sdate,$edate) {
            $qb = $this->createQueryBuilder('u')
            ->andWhere('u.createdAt >= :sDate')->setParameter('sDate',  $sdate)
            ->andWhere('u.createdAt <= :eDate')->setParameter('eDate',  $edate)
            ->select('count(u.id) as Admimssion')
            ->getQuery()
            ->getSingleScalarResult();
            return $qb;
            
    }
    //ADMISSION_DISCHARGED=1
//////////////////////////////////////////////////////////
    public function total_discharges($sdate,$edate) {
        $qb = $this->createQueryBuilder('u')
        ->andWhere("u.status =1")
        ->andWhere('u.createdAt >= :sDate')->setParameter('sDate',  $sdate)
        ->andWhere('u.createdAt <= :eDate')->setParameter('eDate',  $edate)
        ->select('count(u.id) as Admimssion')
        ->getQuery()
        ->getSingleScalarResult();
        return $qb;
        
}
///////////////////////////////////////////////
    public function totalBeds($sdate,$edate) {
        $qb = $this->createQueryBuilder('u')
        ->andWhere('u.createdAt >= :sDate')->setParameter('sDate',  $sdate)
        ->andWhere('u.createdAt <= :eDate')->setParameter('eDate',  $edate)
        ->select('count(u.id) as Admimssion')
        ->getQuery()
        ->getSingleScalarResult();
        return $qb;
        
}

public function lengthStay($sdate,$edate) {
    $qb = $this->createQueryBuilder('u')
    ->andWhere('u.createdAt >= :sDate')->setParameter('sDate',  $sdate)
    ->andWhere('u.createdAt <= :eDate')->setParameter('eDate',  $edate)
    ->select('SUM(u.duration) as Admimssion')
    //->select('count(u.id) as Admimssion')
    ->getQuery()
    ->getSingleScalarResult();
    return $qb;
    
}

      
    public function total_emergency($sdate,$edate) {
        $qb = $this->createQueryBuilder('u')
        ->andWhere('u.createdAt >= :sDate')->setParameter('sDate',  $sdate)
        ->andWhere('u.createdAt <= :eDate')->setParameter('eDate',  $edate)
         ->andWhere("u.type =1")
        ->select('count(u.id) as Admimssion')
        ->getQuery()
        ->getSingleScalarResult();
        return $qb;
        
}
public function non_emergency($sdate,$edate) {
    $qb = $this->createQueryBuilder('u')
    ->andWhere('u.createdAt >= :sDate')->setParameter('sDate',  $sdate)
    ->andWhere('u.createdAt <= :eDate')->setParameter('eDate',  $edate)
    ->andWhere("u.type =2")
    ->select('count(u.id) as Admimssion')
    ->getQuery()
    ->getSingleScalarResult();
    return $qb;
    
}

    public function  new_admimssions()
    {
        $today = new \DateTime();
        $qb = $this->createQueryBuilder('h')
            ->select('count(h.id) as Admimssion')
            ->andWhere('h.createdAt like :createdAt')->setParameter('createdAt', $today->format("Y-m-d")."%")
           // ->andWhere("a.status =2")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

   public function total_deaths($sdate,$edate) {
        $qb = $this->createQueryBuilder('u')
        ->andWhere('u.createdAt >= :sDate')->setParameter('sDate',  $sdate)
        ->andWhere('u.createdAt <= :eDate')->setParameter('eDate',  $edate)
        ->andWhere("u.outcome =1")
        ->select('count(u.id) as Admimssion')
        ->getQuery()
        ->getSingleScalarResult();
        return $qb;
        
   }





   public function total_death()
   {
       $qb = $this->createQueryBuilder('a')
           ->select('count(a.id) as Admimssion')
          // ->andWhere("a.status =1")
           ->andWhere("a.outcome =1")
           ->getQuery()
           ->getSingleScalarResult();
         
           return $qb;
           
     }
    public function total_transfer()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Admimssion')
            ->andWhere("a.status =2")
          
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function total_improves()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Admimssion')
          //  ->andWhere("a.status =3")
            ->andWhere("a.outcome =3")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
    }
      
        public function total_self_dischage()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Admimssion')
            //  ->andWhere("a.status =3")
            ->andWhere("a.outcome =7")
            ->getQuery()
            ->getSingleScalarResult();
            return $qb; 
    
       }
    public function total_abscond()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Admimssion')
           // ->andWhere("a.status =2")
            ->andWhere("a.outcome =2")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function total_referral()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Admimssion')
            //->andWhere("a.status =6")
            ->andWhere("a.outcome =6")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }
    public function total_nochange()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Admimssion')
           // ->andWhere("a.status =5")
            ->andWhere("a.outcome =5")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }


 public function getAdvancedSearch($ward=null,$unit=null,$date=null){
  
    $today = new \DateTime();
    return $this->createQueryBuilder('a')
    ->join('a.bed', 'b')
    ->join('b.room','r')
    ->join('r.unit','u')

    ->andWhere('r.unit =:unit')->setParameter('unit',  $unit)
    ->andWhere('u.ward =:ward')->setParameter('ward',  $ward)
    ->andWhere('a.createdAt like :date')->setParameter('date', $today->format("Y-m-d")."%")
    ->orderBy('a.id', 'DESC')
    ->getQuery(); 
}



/////////////////////////////////////////////////////////////////////////////
public function  getWaitingList($ward) {
    $today = new \DateTime();
return $this->createQueryBuilder('a')
->join('a.bed', 'b')
->join('b.room','r')
->join('r.unit','u')
->andWhere('u.ward =:ward')->setParameter('ward',  $ward)
//->andWhere('a.createdAt like :date')->setParameter('date', $today->format("Y-m-d")."%")
->andWhere("a.isCheckedIn = 0")
->andWhere("a.status = 0")
->orderBy('a.id', 'DESC')
->getQuery(); 

}


/////////////////////////////////////////////////////////////////////////////
 public function  getNewAdminsions($ward) {
            $today = new \DateTime();
        return $this->createQueryBuilder('a')
        ->join('a.bed', 'b')
        ->join('b.room','r')
        ->join('r.unit','u')
        ->andWhere('u.ward =:ward')->setParameter('ward',  $ward)
        ->andWhere('a.createdAt like :date')->setParameter('date', $today->format("Y-m-d")."%")
        ->andWhere("a.isCheckedIn = 0")
        ->andWhere("a.status = 0")
        ->orderBy('a.id', 'DESC')
        ->getQuery(); 

        }
public function getAdmited($ward) {
        
            return $this->createQueryBuilder('a')
            ->join('a.bed', 'b')
            ->join('b.room','r')
            ->join('r.unit','u')
            ->andWhere("a.status = 0")
            ->andWhere('u.ward =:ward')->setParameter('ward',  $ward)
            ->andWhere("a.isCheckedIn = 1")
            ->orderBy('a.id', 'DESC')
            ->getQuery(); 
        }

public function getDischarges($ward) {
        return $this->createQueryBuilder('a')
        ->join('a.bed', 'b')
        ->join('b.room','r')
        ->join('r.unit','u')
        ->andWhere('u.ward =:ward')->setParameter('ward',  $ward)
        ->andWhere("a.status = 1")
        ->orderBy('a.id', 'DESC')
        ->getQuery(); 

        }
///////////////////////////////////////////////////////////////

    public function getAdmissionReport($idate,$fdate) {
         $qb = $this->createQueryBuilder('h');
         $qb->andWhere("h.status = 1"); 
         $qb->andWhere('h.createdAt >= :sDate')->setParameter('sDate',  $idate);
         $qb->andWhere('h.createdAt <= :eDate')->setParameter('eDate',  $fdate);
         $qb->orderBy('h.id', 'ASC');
        return  $qb->getQuery()->getResult(); 
    
    }

    public function getReport($outcome,$sdate,$edate) {
         $qb = $this->createQueryBuilder('a');
         $qb ->andWhere("a.status = 1");
         $qb->andWhere('a.createdAt >= :startDate')->setParameter('startDate',  $sdate);
         $qb->andWhere('a.createdAt <= :endDate')->setParameter('endDate',  $edate);
         $qb->andWhere('a.outcome  = :outcome')->setParameter('outcome',  $outcome);
         $qb->orderBy('a.id', 'ASC');
        return  $qb->getQuery()->getResult(); 
    
    }

    public function getReferrals($sdate,$edate) {
        $qb = $this->createQueryBuilder('a');
        $qb ->andWhere("a.outcome = 6");
        $qb->andWhere('a.createdAt >= :startDate')->setParameter('startDate',  $sdate);
        $qb->andWhere('a.createdAt <= :endDate')->setParameter('endDate',  $edate);
        $qb->orderBy('a.id', 'ASC');
        return  $qb->getQuery()->getResult(); 
    
    }
    public function getReferral($ward,$sdate,$edate) {
        $qb = $this->createQueryBuilder('a');
        $qb ->andWhere("a.outcome = 6");
        $qb->andWhere('a.createdAt >= :startDate')->setParameter('startDate',  $sdate);
        $qb->andWhere('a.createdAt <= :endDate')->setParameter('endDate',  $edate);
      //  $qb->andWhere('a.bed.room.unit.ward  = :ward')->setParameter('ward',  $ward);
        $qb->orderBy('a.id', 'ASC');
        return  $qb->getQuery()->getResult(); 
    
    }

    public function getLastAdmission(Patient $patient){
        $qb= $this->createQueryBuilder('a');
        $qb->andWhere("a.patient = '".$patient->getId()."'");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }


    public function getAdmission($patient)

    {
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.patient =:patient')->setParameter('patient', $patient);
        $qb->orderBy('h.id', 'DESC');
      //  $qb->setMaxResults(2);
        return  $qb->getQuery()->getResult();
    }


    // public function findUser($search = [])
    // {
    
    //     $qb = $this->createQueryBuilder('u');
    //     if (isset($search['name'])) {
    //           $names = explode(" ", $search['name']);
    //         if (sizeof($names) == 3) {
               
    //             $qb->andWhere('u.firstName = :fname')
    //                 ->setParameter('fname', $names[0])

    //                 ->andWhere('u.middleName = :mname')
    //                 ->setParameter('mname', $names[1])
    //                 ->andWhere('u.lastName = :lname')
    //                 ->setParameter('lname', $names[2]);
    //         } else if (sizeof($names) == 2) {

    //             $qb->andWhere('u.firstName = :fname')
    //                 ->setParameter('fname', $names[0])

    //                 ->andWhere('u.middleName = :mname')
    //                 ->setParameter('mname', $names[1]);
    //         } 
    //         else if (sizeof($names) == 1) {

            
    //             $qb=$qb->andWhere("u.firstName LIKE '%" . $names[0] . "%' or u.middleName LIKE '%" . $names[0] . "%' or u.lastName LIKE '%" . $names[0] . "%' ");
              
    //         }
    //     }
    //      if (isset($search['gender'])) {
    //         $qb->andWhere('u.sex = :gnd')
    //             ->setParameter('gnd', $search['gender']);
    //     }

    //     return $qb->orderBy('u.id', 'ASC')
    //         ->getQuery();
            
          
    // }

    // /**
    //  * @return Admimssion[] Returns an array of Admimssion objects
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
    public function findOneBySomeField($value): ?Admimssion
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
