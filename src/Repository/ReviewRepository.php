<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\Query;
/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findLatest(int $page = 1): Pagerfanta
    {
        /*
        return $this->createQueryBuilder('r', $user)
            ->where('r.something = :value')->setParameter('value', $value)
            ->orderBy('$user.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
         */
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT p
                FROM App:Review p 
                ORDER BY p.publishedAt DESC
            ")
          //  ->setParameter('author', 'author')
        ;

        return $this->createPaginator($query, $page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Review::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
/*
    public function findLatest(int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT p
                FROM App:Review p
                WHERE p.isPublicReview = TRUE
                ORDER BY p.publishedAt DESC
            ")
           // ->setParameter('now', new \DateTime())
        ;

        return $this->createPaginator($query, $page);
    }
*/
}
