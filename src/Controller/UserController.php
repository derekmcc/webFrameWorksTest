<?php
/**
 * Summary for user controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * The start of the user controller
 *
 * @Route("/user", name="user_")
 * @package App\Controller
 */
class UserController extends Controller
{
    /**
     * Function that return the user index page
     *
     * @Route("/", name="index")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAll();
        $reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users]);
    }

    /**
     * Function for managing the users account
     *
     * @Route("/account", name="account")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function userAccount()
    {
        $recipes= $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAll();
        $user = $this->getUser();
        $reviews= $this->getDoctrine()
            ->getRepository(Review::class)
            ->findAll();
        $user = $this->getUser();
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'recipes' => $recipes,
            'reviews' => $reviews,
        ]);

    }

    /**
     * Function for managing new users
     *
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $role = ['ROLE_USER'];
            $user->setRoles($role);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Function for showing a users details
     *
     * @Route("/{id}", name="show")
     * @Method("GET")
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAll();
        $reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findAll();
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'reviews' => $reviews,
            'recipes' => $recipes,
        ]);
    }

    /**
     * Function for editing user details
     *
     * @Route("/{id}/edit", name="edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_account', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Function for deleting a user account
     *
     * @Route("/{id}", name="delete")
     * @Method("DELETE")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, User $user)
    {

        if ($user->getId() == $this->getUser()->getId()){
            $this->get('security.token_storage')->setToken(null);
        }

        if (!$this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('user_index');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_index');
    }
}
