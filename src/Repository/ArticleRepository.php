<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findLastPublished(int $limit): array
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.publie = :val')
        ->setParameter('val', true)
        ->orderBy('a.id', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
}

