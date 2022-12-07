<?php

namespace App\Repository;

use App\Entity\Diploma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Diploma>
 *
 * @method Diploma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diploma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diploma[]    findAll()
 * @method Diploma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiplomaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Diploma::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Diploma $entity, bool $flush = true): void
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

    public function getDiploma($student)

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
    public function remove(Diploma $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Diploma[] Returns an array of Diploma objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Diploma
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
