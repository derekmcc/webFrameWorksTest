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
        // Act
        $this->client->request('GET','/error');
        $searchText = 'No found error';

        // Arrange
        $statusCode = $this->client->getResponse()->getStatusCode();
        $content = $this->client->getResponse()->getContent();

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert
        $this->assertEquals(200, $statusCode);

        // Assert
        $this->assertContains(
            $searchTextLowerCase,
            $contentLowerCase
        );

    }
}