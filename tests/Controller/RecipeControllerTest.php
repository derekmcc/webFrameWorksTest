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
     * @dataProvider publicRecipeUrls
     */
    public function testRecipePublicUrls($url)
    {
        // Arrange
        $this->client->request('GET',$url);
        $crawler = $this->client->getResponse();
        $searchText = 'Drinks Index';


        // Act
        $statusCode = $this->client->getResponse()->getStatusCode();
        $content = $this->client->getResponse()->getContent();

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert
        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        // Assert
        $this->assertContains(
            $searchTextLowerCase,
            $contentLowerCase
        );
    }

    public function publicRecipeUrls()
    {
        return array(
            ['/recipe/'],
            ['/recipe/showRecipe'],
        );
    }

    public function testEditRecipe()
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $searchText = 'Edit Drink';


        // Act
        $client->request('GET', '/recipe/1/edit');
        $content = $client->getResponse()->getContent();

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert
        $this->assertContains(
            $searchTextLowerCase,
            $contentLowerCase
        );
    }

    public function testNewRecipe()
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $searchText = 'New Drink';


        // Act
        $client->request('GET', '/recipe/new');
        $content = $client->getResponse()->getContent();

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert
       // $this->assertContains(
         //   $searchTextLowerCase,
           // $contentLowerCase
        //);
    }

    public function testAdminHomePage()
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->request('GET', '/admin');

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
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