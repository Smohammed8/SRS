<?php

namespace App\Repository;

use App\Entity\BedPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BedPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method BedPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method BedPrice[]    findAll()
 * @method BedPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BedPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BedPrice::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(BedPrice $entity, bool $flush = true): void
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
    public function remove(BedPrice $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

public function getPrice(){

    $qb = $this->createQueryBuilder('i')
     ->select('i.price')
     ->Where('i.id= :id')->setParameter('id', 1);
     return  $qb->getQuery()->getSingleScalarResult();
    // ->getOneOrNullResult

}
   

    // /**
    //  * @return BedPrice[] Returns an array of BedPrice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BedPrice
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
