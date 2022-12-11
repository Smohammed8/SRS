<?php

namespace App\Repository;

use App\Entity\Slip;
use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Slip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slip[]    findAll()
 * @method Slip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Slip::class);
    }

    public function getQuery($search = null)
    {
        $qb = $this->createQueryBuilder('a');
        //$qb->andWhere("a.name like '%$search%'");
        //$qb ->andWhere("a.status = 1");
        $qb->orderBy('a.id', 'DESC');
        return  $qb->getQuery();
    }

  
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Slip $entity, bool $flush = true): void
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
    public function remove(Slip $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getSlips($student,$search=null)

    {
       // $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
      //  $qb->andWhere("h.status = '1'");
        $qb->andWhere('h.student= :student')->setParameter('student', $student);
       // $qb->andWhere('h.appointedAt like :appointedAt')->setParameter('appointedAt', $today->format("Y-m-d")."%");
        $qb->orderBy('h.id', 'DESC');
        return  $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Slip[] Returns an array of Slip objects
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
    public function findOneBySomeField($value): ?Slip
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
