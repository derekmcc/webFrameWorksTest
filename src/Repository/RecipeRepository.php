<?php
/**
 * Recipe repository for executing queries on recipe items.
 */

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\Query;

/**
 * Start of the recipe repository class
 * Class RecipeRepository
 * @package App\Repository
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    /**
     * RecipeRepository constructor
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Query to search for a recipe
     * @param string $rawQuery
     * @return array
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
     * Removes all non-alphanumeric characters except whitespaces
     * @param string $query
     * @return string
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return preg_replace('/[^[:alnum:] ]/', '', trim(preg_replace('/[[:space:]]+/', ' ', $query)));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant
     * @param string $searchQuery
     * @return array
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', mb_strtolower($searchQuery)));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }

    /**
     * Returns the recipes based on the date they were created to the paginator
     * @param int $page
     * @return Pagerfanta
     */
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

    /**
     * Returns the recipes based on the date they were created to the paginator for non logged
     * in users
     * @param int $page
     * @return Pagerfanta
     */
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

    /**
     * Returns the recipes based on the date they were created to the paginator for the
     * author of the recipe
     * @param int $page
     * @param $user
     * @return Pagerfanta
     */
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

    /**
     * Query for date range for public users
     * @param string $date1
     * @param string $date2
     * @return mixed
     */
    public function findPublicRecipesByDate(string $date1, string $date2)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.publishedAt >= '{$date1}' AND r.publishedAt <= '{$date2}'
                AND r.isPublic = true 
                ORDER BY r.publishedAt DESC
            ")
            ->getResult()
        ;
        return $query;
    }

    /**
     * Query for date range for public users and the author
     * @param string $date1
     * @param string $date2
     * @return mixed
     */
    public function findPublicAndAuthorRecipesByDate(string $date1, string $date2, $user)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.publishedAt >= '{$date1}' AND r.publishedAt <= '{$date2}'
                AND r.isPublic = true 
                OR r.author = '{$user->getId()}'
                ORDER BY r.publishedAt DESC
            ")
            ->getResult()
        ;
        return $query;
    }

    /**
     * Query for date range for admins
     * @param string $date1
     * @param string $date2
     * @return Query
     */
    public function findAdminRecipesByDate(string $date1, string $date2)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.publishedAt >= '{$date1}' AND r.publishedAt <= '{$date2}'
                ORDER BY r.publishedAt DESC
            ")
            ->getResult()
        ;
        return $query;
    }

    /**
     * Query that finds recipes between a certain price range
     * @param string $sort
     * @return mixed
     */
    public function findRecipesByPriceRange(string $sort)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.price = '{$sort}'
                ORDER BY r.publishedAt DESC
            ")
            ->getResult()
        ;
        return $query;
    }

    /**
     * Query that finds recipes between a certain price range
     * @param string $sort
     * @return mixed
     */
    public function findPublicRecipesByPriceRange(string $sort)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.price = '{$sort}'
                AND r.isPublic = true 
                ORDER BY r.publishedAt DESC
            ")
            ->getResult()
        ;
        return $query;
    }

    /**
     * Query that finds recipes between a certain price range
     * @param string $sort
     * @return mixed
     */
    public function findPublicAndByAuthorRecipesByPriceRange(string $sort, $user)
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.price = '{$sort}'
                AND r.isPublic = true 
                OR r.author = '{$user->getId()}'
                ORDER BY r.publishedAt DESC
            ")
            ->getResult()
        ;
        return $query;
    }

    /**
     * Creates a paginator for the recipes
     * @param Query $query
     * @param int $page
     * @return Pagerfanta
     */
    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Recipe::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
