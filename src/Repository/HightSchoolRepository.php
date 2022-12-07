<?php

namespace App\Repository;

use App\Entity\HightSchool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HightSchool>
 *
 * @method HightSchool|null find($id, $lockMode = null, $lockVersion = null)
 * @method HightSchool|null findOneBy(array $criteria, array $orderBy = null)
 * @method HightSchool[]    findAll()
 * @method HightSchool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HightSchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HightSchool::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(HightSchool $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getHighSchool($student)

    {
        $today = new \DateTime();
        $qb= $this->createQueryBuilder('h');
        $qb->andWhere('h.student =:student_id')->setParameter('student_id', $student);
        $qb->orderBy('h.id', 'DESC');
      //  $qb->setMaxResults(1);

        return  $qb->getQuery()->getResult();
        
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(HightSchool $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return HightSchool[] Returns an array of HightSchool objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HightSchool
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
