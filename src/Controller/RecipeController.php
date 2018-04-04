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
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="blog_index_paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function index(int $page, string $_format, RecipeRepository $recipes)
    {
        $latestPosts = $recipes->findLatest($page, $this->getUser());

        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('recipe/index.'.$_format.'.twig', ['recipes' => $latestPosts]);
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
       // $recipe->setRequestRecipePublic($this->getUser()->setMakeReviewsPublic($this->getUser()));
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $recipe->getImage();
            $fileName = $fileUploader->upload($file);
            $recipe->setImage($fileName);
            $recipe->setIsPublic(false);
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
     * @Route("/{id}", name="show")
     * @Method("GET")
     */
    public function show(Recipe $recipe)
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * @Route("/{id}/request", name="request")
     * @Method({"GET", "POST"})
     *
     */
    public function request(Request $request, Recipe $recipe)
    {

        //fixes issue if file not found when going to form
        if($recipe->getImage() == null)
        {
            $recipe->setImage(
                new File($this->getParameter('images_directory').'/'.$recipe->getImage())
            );
        }
        $form = $this->createForm(RequestType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          //  $recipe->setImage(
            //    new File($this->getParameter('images_directory').'/'.$recipe->getImage())
          //  );
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('request_edit', ['id' => $recipe->getId()]);
        }
        return $this->render('request/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
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
