<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findHomePage($skip = 0, $take = 5, $onlyPublic = false)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p as post, COUNT(c.id) AS nbComments')
            ->leftJoin('p.comments', 'c')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC')
            ->setFirstResult($skip)
            ->setMaxResults($take);

        if ($onlyPublic === true):
            $queryBuilder = $queryBuilder->where('p.public = true');
        endif;

        $results = $queryBuilder->getQuery()->getResult();
        $posts = [];
        foreach ($results as $result):
            $post = $result['post'];
            $post->setNbComments($result['nbComments']);
            array_push($posts, $post);
        endforeach;
        return $posts;
    }

//     /**
//      * @return Post[] Returns an array of Post objects
//      */
    /*public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }*/


    /*public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }*/

}
