<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\Query;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recipe::class);
    }
    /**
     * @return Recipe[]
     */
    public function findBySearchQuery(string $rawQuery): array
    {

        $query = $this->sanitizeSearchQuery($rawQuery);
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('r');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('r.title LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.$term.'%')
            ;
        }

        return $queryBuilder
            //->orderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Removes all non-alphanumeric characters except whitespaces.
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return preg_replace('/[^[:alnum:] ]/', '', trim(preg_replace('/[[:space:]]+/', ' ', $query)));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', mb_strtolower($searchQuery)));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }

    public function findLatestRecipes(int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r 
                ORDER BY r.publishedAt DESC
            ")
        ;

        return $this->createPaginator($query, $page);
    }

    public function findLatestPublicRecipes(int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.isPublic = TRUE
                ORDER BY r.publishedAt DESC
            ")
        ;
        return $this->createPaginator($query, $page);
    }

    public function findRecipesByAuthor(int $page = 1, $user): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.author = '{$user->getId()}'
                OR r.isPublic = true
                ORDER BY r.publishedAt DESC
            ")
        ;
        return $this->createPaginator($query, $page);
    }

    public function findRecipesByDate(string $date1, string $date2, int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.publishedAt BETWEEN '{$date1}' AND '{$date2}'
                ORDER BY r.publishedAt DESC
            ")
        ;
        return $this->createPaginator($query,$page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Recipe::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

//    public function findRecipesByAuthorToDelete($user)
//    {
//        return $this->getEntityManager()
//            ->createQuery("
//                SELECT r
//                FROM App:Recipe r
//                WHERE r.author = '{$user->getId()}'
//            ")
//        ;
//    }
    /*
    public function findLatest(int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p, a, t
                FROM App:Recipe p
                JOIN p.author a
                LEFT JOIN p.reviews t
                WHERE p.publishedAt <= :now
                ORDER BY p.publishedAt DESC
            ')
            ->setParameter('now', new \DateTime())
        ;

        return $this->createPaginator($query, $page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Recipe::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }*/
}
