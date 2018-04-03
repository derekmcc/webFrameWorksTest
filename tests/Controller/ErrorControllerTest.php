<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:52
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }


    public function testErrorResponseOkay()
    {
        // Arrange
        $this->client->request('GET','/error');

        // Act
        $statusCode = $this->client->getResponse()->getStatusCode();
        // Assert
        $this->assertEquals(200, $statusCode);

    }
}