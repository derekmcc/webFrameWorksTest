<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:37
 */

namespace App\Tests\Controller;


use App\Controller\AdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testCanCreateObject()
    {
        // Arrange
        $adminController = new AdminController();

        // Act

        // Assert
        $this->assertNotNull($adminController);
    }


    /**
     * @dataProvider getUrlsForAdminUsers
     */
    public function testAccessDeniedForRegularUsers($httpMethod, $url)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'john_user',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->request($httpMethod, $url);

        // Assert
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getUrlsForAdminUsers
     */
    public function testAccessForAdminUsers($httpMethod, $url)
    {
        // Arrange
        $searchText = 'Admin Page';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->request($httpMethod, $url);
        $content = $client->getResponse()->getContent();

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert
        $this->assertContains(
            $searchTextLowerCase,
            $contentLowerCase
        );
    }

    public function getUrlsForAdminUsers()
    {
        yield ['GET', '/admin'];
    }
}