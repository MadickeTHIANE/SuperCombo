<?php

namespace App\Repository;

use App\Entity\BlogDiscussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogDiscussion[]    findAll()
 * @method BlogDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogDiscussion::class);
    }

    public function searchByTerm($term)
    {
        $queryBuilder = $this->createQueryBuilder('blog_discussion');

        $query = $queryBuilder
            ->select('blog_discussion')
            ->orWhere('blog_discussion.contenu LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery();

        return $query->getResult();
    }

    // /**
    //  * @return BlogDiscussion[] Returns an array of BlogDiscussion objects
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
    public function findOneBySomeField($value): ?BlogDiscussion
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
