<?php
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
 * @Route("/recipe", name="recipe_")
 */
class RecipeController extends Controller
{
    /**
     *
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="index")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
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
     * @Route("/showRecipe", name="showRecipe")
     *
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
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
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
            return $this->redirectToRoute('recipe_showRecipe', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/search", name="search")
     * @Method("GET")
     */
    public function search(Request $request, RecipeRepository $recipes): Response
    {
        //dump($recipes);

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
               // 'url' => $this->generateUrl('recipe_search_aa'),
            ];
        }

        return $this->render('recipe/search.html.twig', ['recipes' => $results]);
    }
    /**
     * @Route("/{id}", name="show")
     * @Method("GET")
     */
    public function show(Recipe $recipe)
    {
        if (!$recipe) {
            throw $this->createNotFoundException(
                'No Drink found for id ' . $recipe->getId()
            );
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @Method({"GET", "POST"})
     *
     */
    public function edit(Request $request, Recipe $recipe)
    {
        //fixes issue if file not found when going to form
        if($recipe->getImage() == null)
        {
            $recipe->setImage(
                new File($this->getParameter('images_directory').'/'.$recipe->getImage())
            );
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('recipe_edit', ['id' => $recipe->getId()]);
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
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
     * @param Recipe $recipe
     * @Route("/{id}/publish", requirements={"id" = "\d+"}, name="publish_recipe")
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
     * @param Recipe $recipe
     * @Route("/{id}/reject", requirements={"id" = "\d+"}, name="reject_recipe")
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
     * @param Recipe $recipe
     * @Route("/{id}/request", requirements={"id" = "\d+"}, name="request_publish")
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
