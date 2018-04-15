<?php
/**
 * This is the recipe controller summary
 */


namespace App\Controller;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Form\RequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class RecipeController
 *
 * @package App\Controller
 * @Route("/recipe", name="recipe_")
 */
class RecipeController extends Controller
{
    /**
     * Function for managing the recipe index page
     *
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="index")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     * @param int $page
     * @param string $_format
     * @param RecipeRepository $recipes
     * @return Response
     */
    public function index(int $page, string $_format, RecipeRepository $recipes)
    {
        $user = $this->getUser();

        if ($user == null) {
            $latestRecipes = $recipes->findLatestPublicRecipes($page);
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            $latestRecipes = $recipes->findLatestRecipes($page);
        } else {
            $latestRecipes = $recipes->findRecipesByAuthor($page, $user);
        }
        return $this->render('recipe/index.'.$_format.'.twig', ['recipes' => $latestRecipes]);
    }
    /**
     * Function that is used for returning recipes between a certain date range
     *
     * @Route("/showRecipe", name="showRecipe")
     * @return Response
     */
    public function showRecipe()
    {
        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAll();
        return $this->render('recipe/showDrinks.html.twig', ['recipes' => $recipes]);
    }

    /**
     * Function for creating new recipes
     *
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return RedirectResponse|Response
     */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $recipe = new Recipe();
        $recipe->setAuthor($this->getUser());
        $recipe->setRequestRecipePublic(false);
        $recipe->setPublishedAt(new \DateTime('now '));
       // $recipe->setRequestRecipePublic($this->getUser()->setMakeReviewsPublic($this->getUser()));
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $recipe->getImage();
            $fileName = $fileUploader->upload($file);
            $recipe->setImage($fileName);
            $recipe->setIsPublic(false);
            $recipe->setRequestRecipePublic(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();
            return $this->redirectToRoute('recipe_index', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Function used to return search results
     *
     * @Route("/search", name="search")
     * @Method("GET")
     * @param Request $request
     * @param RecipeRepository $recipes
     * @return Response
     */
    public function search(Request $request, RecipeRepository $recipes): Response
    {
        $query = $request->query->get('q', '');
        $foundRecipes = $recipes->findBySearchQuery($query);

        $results = [];
        foreach ($foundRecipes as $recipe) {
            $results[] = [
                'id' => $recipe->getId(),
                'title' => $recipe->getTitle(),
                'image' => $recipe->getImage(),
                'author' => $recipe->getAuthor(),
                'summary' => $recipe->getSummary(),
                'isPublic' => $recipe->getIsPublic(),
            ];
        }
        return $this->render('recipe/search.html.twig', ['recipes' => $results]);
    }

    /**
     * Function used for finding recipes between a certain date range
     *
     * @Route("/date", name="date")
     * @Method("GET")
     * @param Request $request
     * @param RecipeRepository $recipes
     * @return Response
     */
    public function searchDates(Request $request, RecipeRepository $recipes): Response
    {
        $date1 = $request->query->get('date1', '');
        $date2 = $request->query->get('date2', '');

        $user = $this->getUser();

        if ($user == null) {
            $foundRecipes = $recipes->findPublicRecipesByDate($date1,$date2);
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            $foundRecipes = $recipes->findAdminRecipesByDate($date1,$date2);
        } else {
            $foundRecipes = $recipes->findPublicAndAuthorRecipesByDate($date1,$date2,$user);
        }
        return $this->render('recipe/showDrinks.html.twig', ['recipes' => $foundRecipes]);
    }

    /**
     * Function used to find recipes between a certain price range
     *
     * @Route("/price", name="price")
     * @Method("GET")
     * @param Request $request
     * @param RecipeRepository $recipes
     * @return Response
     */
    public function searchByPrice(Request $request, RecipeRepository $recipes): Response
    {
        $priceRange = $request->query->get('sort', '');
        if ($priceRange == 1){
            $sort = 'Under €10';
        } else if ($priceRange == 2) {
            $sort = '€11-20';
        } else if ($priceRange == 3) {
            $sort = '€21-30';
        } else if ($priceRange == 4) {
            $sort = '€31-40';
        } else if ($priceRange == 5) {
            $sort = 'Over €40';
        }

        $user = $this->getUser();

        if ($user == null) {
            $foundRecipes = $recipes->findRecipesByPriceRange($sort);
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            $foundRecipes = $recipes->findPublicRecipesByPriceRange($sort);
        } else {
            $foundRecipes = $recipes->findPublicAndByAuthorRecipesByPriceRange($sort, $user);
        }
        return $this->render('recipe/showDrinks.html.twig', ['recipes' => $foundRecipes]);
    }

    /**
     * Function used to return a certain recipes details
     *
     * @Route("/{id}", name="show")
     * @Method("GET")
     * @param Recipe $recipe
     * @return Response
     */
    public function show(Recipe $recipe)
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * Function used to manage the editing of a certain recipe
     *
     * @Route("/{id}/edit", name="edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Recipe $recipe
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Recipe $recipe)
    {
        // --Does'nt work - suppose to allow non selecting of image on edit form
//        if($recipe->getImage() == null)
//        {
//            $recipe->setImage(
//                new File($this->getParameter('images_directory').'/'.$recipe->getImage())
//            );
//        }
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Function used to delete the chosen recipe
     *
     * @Route("/{id}", name="delete")
     * @Method("DELETE")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Recipe $recipe
     * @return RedirectResponse
     */
    public function delete(Request $request, Recipe $recipe)
    {
        if (!$this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('recipe_index');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($recipe);
        $em->flush();
        return $this->redirectToRoute('recipe_index');
    }

    /**
     * Function that sets a recipe to public
     *
     * @param Recipe $recipe
     * @Route("/{id}/publish", requirements={"id" = "\d+"}, name="publish_recipe")
     * @Security("has_role('ROLE_ADMIN')")
     * @return RedirectResponse
     */
    public function setRecipeToPublic(Recipe $recipe)
    {
        $recipe->setIsPublic(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($recipe);
        $em->flush();
        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }

    /**
     * Function that rejects a users request for a recipe to be public
     *
     * @param Recipe $recipe
     * @Route("/{id}/reject", requirements={"id" = "\d+"}, name="reject_recipe")
     * @Security("has_role('ROLE_ADMIN')")
     * @return RedirectResponse
     */
    public function rejectPublicRequest(Recipe $recipe)
    {
        $recipe->setRequestRecipePublic(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($recipe);
        $em->flush();
        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }

    /**
     * Function used to make a request for a recipe to be made public
     *
     * @param Recipe $recipe
     * @Route("/{id}/request", requirements={"id" = "\d+"}, name="request_publish")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function setMakeRequestPublic(Recipe $recipe)
    {
        $recipe->setRequestRecipePublic(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($recipe);
        $em->flush();
        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }
}
