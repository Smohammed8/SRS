<?php

namespace App\Repository;
use App\Entity\Room;
use App\Entity\Unit;
use App\Entity\Ward;
use App\Entity\Bed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function getQuery($search=null)
    {
        $qb= $this->createQueryBuilder('a');
        $qb->andWhere("a.name like '%$search%'");
       // $qb ->andWhere("a.status = 0");
        $qb->orderBy('a.id', 'DESC');
           return  $qb->getQuery();
    }

    public function getRooms_in_unit(Unit $unit, $search=null)
    {
        $qb= $this->createQueryBuilder('h');

        $qb->andWhere('h.unit= :unit')->setParameter('unit',$unit);
        $qb->andWhere("h.name like '%$search%'");
         $qb->orderBy('h.id', 'ASC');
           return  $qb->getQuery();
    }

    public function total_rooms()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id) as Room')
            ->getQuery()
            ->getSingleScalarResult();
          
            return $qb;
            
    }

    // /**
    //  * @return Room[] Returns an array of Room objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
