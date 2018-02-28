<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/recipe", name="recipe_")
 */
class RecipeController extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index()
    {
        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAll();

        return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
    }

    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('recipe_edit', ['id' => $recipe->getId()]);
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
     * @Route("/{id}/edit", name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Recipe $recipe)
    {
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
}
