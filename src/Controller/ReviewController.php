<?php
/**
 * Summary for review controller.
 */

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\File;

/**
 * The review controller class
 *
 * @package App\Controller
 * @Route("/review", name="review_")
 */
class ReviewController extends Controller
{

    /**
     * Function used to return the review index page
     *
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="index")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     * @param int $page
     * @param string $_format
     * @param ReviewRepository $reviews
     * @return Response
     */
    public function index(int $page, string $_format, ReviewRepository $reviews)
    {
        $user = $this->getUser();

        if ($user == null) {
            $latestPosts = $reviews->findLatestPublicReviews($page);
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            $latestPosts = $reviews->findLatestReviews($page);
        } else {
            $latestPosts = $reviews->findReviewsByAuthor($page, $user);
        }
        return $this->render('review/index.'.$_format.'.twig', ['reviews' => $latestPosts]);
    }

    /**
     * Function used to sort the reviews by the number of stars
     *
     * @Route("/showReview", name="showReview")
     * @param Request $request
     * @param ReviewRepository $review
     * @return Response
     */
    public function showReview(Request $request, ReviewRepository $review)
    {
        $sort= $request->query->get('sort', '');
        if ($sort == 2){
            $sortBy = 'DESC';
        } else {
            $sortBy = 'ASC';
        }
        $reviews = $review->findReviewsByNumberOfVotes($sortBy);
        return $this->render('review/showReviews.html.twig', ['reviews' => $reviews]);
    }

    /**
     * Function for creating a new review
     *
     * @Route("/new/{recipeID}",  defaults={"recipeID" = 0}, name="new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param Recipe $recipeID
     * @return RedirectResponse|Response
     */
    public function new(Request $request, FileUploader $fileUploader, Recipe $recipeID)
    {
        $review = new Review();
        $review->setAuthor($this->getUser());
        $review->setIsPublicReview(false);
        $em = $this->getDoctrine()->getManager();

        $recipeClassID = $em->getRepository('App:Recipe')->find($recipeID);
        $review->setRecipe($recipeClassID);

        $review->setPublishedAt(new \DateTime('now '));
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setRequestReviewPublic(false);
            $file = $review->getImage();
            $fileName = $fileUploader->upload($file);
            $review->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            $recipe = $review->getRecipe();
            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('review/new.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Function used to show an individual reviews details
     *
     * @Route("/{id}", name="show")
     * @Method("GET")
     * @param Review $review
     * @return Response
     */
    public function show(Review $review)
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    /**
     * Function for editing a review
     *
     * @Route("/{id}/edit", name="edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Review $review
     * @param FileUploader $fileUploader
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Review $review, FileUploader $fileUploader)
    {
        //fixes issue if file not found when going to form
//        if($review->getImage() == null)
//        {
//            // --Does'nt work suppose to allow non selecting of image on edit form
//            $review->setImage(
//                new File($this->getParameter('images_directory').'/'.$review->getImage())
//            );
//        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('review_show', ['id' => $review->getId()]);
        }

        return $this->render('review/edit.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Function for deleting a review
     *
     * @Route("/{id}", name="delete")
     * @Method("DELETE")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Review $review
     * @return RedirectResponse
     */
    public function delete(Request $request, Review $review)
    {
        if (!$this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('review_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        return $this->redirectToRoute('review_index');
    }

    /**
     * Function to set a review to public
     *
     * @param Review $review
     * @Route("/{id}/publish", requirements={"id" = "\d+"}, name="publish_review")
     * @Security("has_role('ROLE_ADMIN')")
     * @return RedirectResponse
     */
    public function setReviewToPublic(Review $review)
    {
        $review->setIsPublicReview(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        return $this->redirectToRoute('review_show', ['id' => $review->getId()]);
    }

    /**
     * Function to reject a users request for a review to be public
     *
     * @param Review $review
     * @Route("/{id}/reject", requirements={"id" = "\d+"}, name="reject_review")
     * @Security("has_role('ROLE_ADMIN')")
     * @return RedirectResponse
     */
    public function rejectPublicRequest(Review $review)
    {
        $review->setRequestReviewPublic(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        return $this->redirectToRoute('review_show', ['id' => $review->getId()]);
    }

    /**
     * Function used to make a request for a review to be public
     *
     * @param Review $review
     * @Route("/{id}/request", requirements={"id" = "\d+"}, name="request_publish")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function setMakeRequestPublic(Review $review)
    {
        $review->setRequestReviewPublic(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        return $this->redirectToRoute('review_show', ['id' => $review->getId()]);
    }

    /**
     * Function to up-vote a review
     *
     * @param Review $review
     * @Route("/{id}/upVote", requirements={"id" = "\d+"}, name="upVote")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function upVoteReview(Review $review)
    {
        $upVote = $review->getUpVotes() + 1;
        $review->setUpVotes($upVote);
        $review->setVotes($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        return $this->redirectToRoute('review_show', ['id' => $review->getId()]);
    }

    /**
     * Function to down-vote a review
     *
     * @param Review $review
     * @Route("/{id}/downVote", requirements={"id" = "\d+"}, name="downVote")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function downVoteReview(Review $review)
    {
        $review->setVotes($this->getUser());

        $downVote = $review->getDownVotes() + 1;
        $review->setDownVotes($downVote);

        $em = $this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();
        return $this->redirectToRoute('review_show', ['id' => $review->getId()]);
    }
}
