<?php

namespace App\Repository;

use App\Entity\Grading;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grading>
 *
 * @method Grading|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grading|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grading[]    findAll()
 * @method Grading[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GradingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grading::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Grading $entity, bool $flush = true): void
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


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Grading $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Grading[] Returns an array of Grading objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Grading
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
