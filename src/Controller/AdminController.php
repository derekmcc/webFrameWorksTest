<?php
/**
 * The admin controller.
 */
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * The start of the controller class
 *
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends Controller
{

    /**
     * Function for the admin index
     *
     * @Route("/myadmin", name="myadmin")
     * @Security("has_role('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
