<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:52
 */

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Annotation\Route;
class RecipeControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }
    /**
     *  queryParametersProvider
     */
    public function testBackendPagesLoadCorrectly()
    {
        $client = static::createClient();

        $client->request('GET', '/recipe');
        $crawler = $client->followRedirect();

        $this->assertEquals('http://localhost/recipe/', $crawler->getUri());
    }

    public function queryParametersProvider()
    {
        return array(
            ['/recipe']

        );
    }

    /**
     * @param $url
     * @param $exepctedLowercaseText
     * @dataProvider basicPagesTextProvider
     */
    /*  public function testListRecipesResponseOkay($url, $exepctedLowercaseText)
      {
          //Arrange
          $httpMethod = 'GET';
          $client = static::createClient();

          //Act
          $client->request($httpMethod, $url);
          $content = $client->getResponse()->getContent();
          $statusCode = $client->getResponse()->getStatusCode();

          //to lower case
          $contentLowerCase = strtolower($content);

          //Assert - status code 200
         // $this->assertSame(Response::HTTP_OK, $statusCode);

          // Assert - expected content
          $this->assertContains( $exepctedLowercaseText, $contentLowerCase
          );
      }
      public function basicPagesTextProvider()
      {
          return [
              ['/recipe/showRecipe/', 'Recipe Index'],
             // ['//recipe/1/show', 'about'],
          ];
      }



      public function testListStudentsHeadingOkay()
      {
          // Arrange
  //        $crawler = $this->client->request('GET', '/student');
          $this->client->request('GET', '/recipe');


          // needle - what we are searching for (as in a haystack!)
          $needle = 'Recipe index';

          // Act
          $content = $this->client->getResponse()->getContent();

          // Assert
          $this->assertContains($needle, $content);
      }*/
}