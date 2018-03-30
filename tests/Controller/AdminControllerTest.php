<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:37
 */

namespace App\Tests\Controller;


use App\Controller\AdminController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends TestCase
{
    public function testCanCreateObject()
    {
        // Arrange
        $adminController = new AdminController();

        // Act

        // Assert
        $this->assertNotNull($adminController);
    }


    /**
     * @dataProvider getUrlsForRegularUsers
     */
  /*  public function testAccessDeniedForRegularUsers($httpMethod, $url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'john_user',
            'PHP_AUTH_PW' => 'pass',
        ]);

        $client->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function getUrlsForRegularUsers()
    {
        yield ['GET', '/'];
        yield ['GET', '/recipe/showRecipe'];
       // yield ['GET', '/en/admin/post/1/edit'];
       // yield ['POST', '/en/admin/post/1/delete'];
    }*/
}