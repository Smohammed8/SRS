<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }


    public function getAppointments($search=null)

    {
        $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
        $qb->Where("h.status = '1'");
        $qb->andWhere('h.appointedAt =:appointedAt')->setParameter('appointedAt', $today->format("Y-m-d")."%");
        $qb->orderBy('h.patient', 'DESC');
        return  $qb->getQuery();
    }


    public function getAppointment($patient)

    {
        $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.patient =:patient')->setParameter('patient', $patient);
        $qb->orderBy('h.id', 'DESC');
        return  $qb->getQuery()->getResult();
    }


    public function getAppoint($date,$patient)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Appointment')
         //   ->andWhere('a.shift =:shift')->setParameter('shift', $shift)
            ->andWhere('a.patient =:patient')->setParameter('patient', $patient)
            ->andWhere('a.appointedAt =:appointedAt')->setParameter('appointedAt', $date->format("Y-m-d")."%")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }


    public function getAppointmentsLimit($date, $shift)
    {
       // $today = new \DateTime();
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Appointment')
            ->andWhere('a.shift =:shift')->setParameter('shift', $shift)
            ->andWhere('a.appointedAt =:appointedAt')->setParameter('appointedAt', $date->format("Y-m-d")."%")
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

    public function filterAppointments($unit=null,$date =null)

    {
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.unit = :unit')->setParameter('unit', $unit);
        $qb->andWhere('h.appointedAt = :appointedAt')->setParameter('appointedAt', $date->format("Y-m-d")."%");
        $qb->orderBy('h.id', 'DESC');
        return  $qb->getQuery()->getResult(); 
    }

    public function getReport($unit,$sdate,$edate) {
        $qb = $this->createQueryBuilder('h');
        $qb ->andWhere("h.status = 2");
        $qb->andWhere('h.appointAt  >= :startDate')->setParameter('startDate',  $sdate);
        $qb->andWhere('h.appointAt <= :endDate')->setParameter('endDate',  $edate);
        $qb->andWhere('h.unit  = :unit')->setParameter('unit',  $unit);
        $qb->orderBy('h.patient', 'DESC');
        return  $qb->getQuery()->getResult(); 
    
    }


    public function getPatientAppointments(Patient $patient,$search=null)

    {
       // $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
        // $qb->andWhere("h.status = '1'");
        // $qb->andWhere("h.status = '0'");
        $qb->andWhere('h.patient= :patient')->setParameter('patient', $patient);
       // $qb->andWhere('h.appointedAt like :appointedAt')->setParameter('appointedAt', $today->format("Y-m-d")."%");
        $qb->orderBy('h.patient', 'DESC');
        return  $qb->getQuery();
    }

    public function getTommorow()

    {
        $tomorrow = new \DateTime('tomorrow');
       // $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.appointedAt like :appointedAt')->setParameter('appointedAt', $tomorrow->format("Y-m-d")."%");
           return  $qb->getQuery();
    }

    public function getPrevious()

    {
        $last_three_days = new \DateTime('yesterday');
       // $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
        //$qb->orWhere('h.woreda like :woreda')->setParameter('woreda',  $search);
        $qb->andWhere('h.appointedAt like :appointedAt')->setParameter('appointedAt',$last_three_days ->format("Y-m-d")."%");
           return  $qb->getQuery();
    }


    // /**
    //  * @return Appointment[] Returns an array of Appointment objects
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
    public function findOneBySomeField($value): ?Appointment
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
