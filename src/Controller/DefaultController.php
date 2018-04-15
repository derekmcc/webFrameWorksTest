<?php
/**
 * The default controller.
 */
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * The start of the default controller class
 *
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends Controller
{


    /**
     * Function that return the homepage
     *
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index()
    {
        $template = 'default/homepage.html.twig';
        $args = [];
        return $this->render($template, $args);
    }
}
