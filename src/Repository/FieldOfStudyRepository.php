<?php

namespace App\Repository;

use App\Entity\FieldOfStudy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FieldOfStudy>
 *
 * @method FieldOfStudy|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldOfStudy|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldOfStudy[]    findAll()
 * @method FieldOfStudy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldOfStudyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldOfStudy::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FieldOfStudy $entity, bool $flush = true): void
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
    public function remove(FieldOfStudy $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return FieldOfStudy[] Returns an array of FieldOfStudy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FieldOfStudy
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
