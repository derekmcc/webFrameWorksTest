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
     * Query that finds recipes based on the date range specified by the user
     * @param string $date1
     * @param string $date2
     * @param int $page
     * @return Pagerfanta
     */
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

    /**
     * Query that finds recipes between a certain price range
     * @param string $sort
     * @param int $page
     * @return Pagerfanta
     */
    public function findRecipesByPriceRange(string $sort, int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT r
                FROM App:Recipe r
                WHERE r.price = '{$sort}'
                ORDER BY r.publishedAt DESC
            ")
        ;
        return $this->createPaginator($query,$page);
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
