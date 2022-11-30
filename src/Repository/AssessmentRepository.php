<?php

namespace App\Repository;

use App\Entity\Assessment;
use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Assessment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assessment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assessment[]    findAll()
 * @method Assessment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssessmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assessment::class);
    }

        public function getAssessments(Patient $patient, $search = null)
    {
        $qb = $this->createQueryBuilder('h');
        $qb->andWhere('h.patient= :patient')->setParameter('patient', $patient);
       // $qb->andWhere("h.name like '%$search%'");
        $qb->orderBy('h.id', 'DESC');
        return  $qb->getQuery();
    }



    public function getDiagnosis($patient)

    {
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.patient =:patient')->setParameter('patient', $patient);
        $qb->orderBy('h.id', 'DESC');
        $qb->setMaxResults(3);
        return  $qb->getQuery()->getResult();
    }



    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Assessment $entity, bool $flush = true): void
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
    public function remove(Assessment $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Assessment[] Returns an array of Assessment objects
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
    public function findOneBySomeField($value): ?Assessment
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
