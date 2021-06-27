<?php

namespace App\Repository;

use App\Entity\BlogBillet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogBillet|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogBillet|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogBillet[]    findAll()
 * @method BlogBillet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogBilletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogBillet::class);
    }

    // /**
    //  * @return BlogBillet[] Returns an array of BlogBillet objects
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
    public function findOneBySomeField($value): ?BlogBillet
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
