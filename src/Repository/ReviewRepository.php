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

    public function findLatestReviews(int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Review r 
                ORDER BY r.publishedAt DESC
            ")
        ;

        return $this->createPaginator($query, $page);
    }

    public function findLatestPublicReviews(int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Review r
                WHERE r.isPublicReview = TRUE
                ORDER BY r.publishedAt DESC
            ")
        ;
        return $this->createPaginator($query, $page);
    }

    public function findReviewsByAuthor(int $page = 1, $user): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Review r
                WHERE r.author = '{$user->getId()}'
                OR r.isPublicReview = true
                ORDER BY r.publishedAt DESC
            ")
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

}
