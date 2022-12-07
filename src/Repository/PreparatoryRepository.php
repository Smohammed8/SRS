<?php

namespace App\Repository;

use App\Entity\Preparatory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Preparatory>
 *
 * @method Preparatory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Preparatory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Preparatory[]    findAll()
 * @method Preparatory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreparatoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preparatory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Preparatory $entity, bool $flush = true): void
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

    public function getPreparatory($student)

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
    public function remove(Preparatory $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Preparatory[] Returns an array of Preparatory objects
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
    public function findOneBySomeField($value): ?Preparatory
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
