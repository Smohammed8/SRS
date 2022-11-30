<?php

namespace App\Repository;

use App\Entity\VitalSign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VitalSign|null find($id, $lockMode = null, $lockVersion = null)
 * @method VitalSign|null findOneBy(array $criteria, array $orderBy = null)
 * @method VitalSign[]    findAll()
 * @method VitalSign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VitalSignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VitalSign::class);
    }

    public function getQuery($search=null)
    {
        $qb= $this->createQueryBuilder('a');
       // $qb->andWhere("a.isCheckedIn =1");
       // $qb->andWhere("a.id like '%$search%'");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }
  

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(VitalSign $entity, bool $flush = true): void
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
    public function remove(VitalSign $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return VitalSign[] Returns an array of VitalSign objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VitalSign
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
