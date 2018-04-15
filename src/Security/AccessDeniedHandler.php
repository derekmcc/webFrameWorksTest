<?php
/**
 * Access denied class which return a 404 if a user tries to access a
 * restricted page for admin user.
 */

namespace App\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Start of the access denied handler class
 * Class AccessDeniedHandler
 * @package App\Security
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * Twig object
     * @var object
     */
    private $twig;

    /**
     * Logger object
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AccessDeniedHandler constructor
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->twig = $container->get('twig');
        $this->logger = $logger;
    }

    /**
     * Returns a error 404 page
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return Response
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $this->logger->error('access denied exception');

        $template = 'error/accessDenied.html.twig';
        $args = [];
        $html = $this->twig->render($template, $args);
        return new Response($html);
    }
}