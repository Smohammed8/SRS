<?php

namespace App\Repository;

use App\Entity\Encounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Encounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Encounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Encounter[]    findAll()
 * @method Encounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncounterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Encounter::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Encounter $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getQuery()
    {
        $qb= $this->createQueryBuilder('a');
       // $qb->andWhere("a.firstName  like '%$search%'");
       // $qb ->andWhere("a.status = 0");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }

    public function getActiveEncounter()
    {
        $qb= $this->createQueryBuilder('a');
        $qb->andWhere('a.assignedTo is NULL');
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }

    public function getEncounter($patient)

    {
        $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.patient =:patient')->setParameter('patient', $patient);
        $qb->orderBy('h.id', 'DESC');
      //  $qb->setMaxResults(1);

        return  $qb->getQuery()->getResult();
        
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Encounter $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Encounter[] Returns an array of Encounter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Encounter
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
