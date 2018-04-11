<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * 
     */
    public function index()
    {
        $template = 'default/homepage.html.twig';
        $args = [];
        return $this->render($template, $args);
    }
}
