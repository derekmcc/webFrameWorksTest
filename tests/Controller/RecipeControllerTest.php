<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:52
 */

namespace App\Tests\Controller;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RecipeControllerTest extends WebTestCase
{
    private $client = null;
    const ID = '2';
    const DELETE_ID = '15';

    public function setUp()
    {
        $this->client = static::createClient();
    }
    /**
     * @dataProvider recipeUrlProvider
     */
    public function testRecipeUrls($url, $content)
    {
        // Arrange
        $this->client->request('GET',$url);
        $crawler = $this->client->getResponse();
        $searchText = $content;


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

    public function recipeUrlProvider()
    {
        return array(
            ['/recipe/', 'Drinks Index'],
            ['/recipe/showRecipe', 'Drinks for Date Range'],
            ['/recipe/' . self::ID, 'Drink Details'],
        );
    }
    public function userNameAndPasswordProvider()
    {
        return [
            ['derek' , 'pass'],
            ['joe_user', 'pass']
        ];
    }

    /**
     * @dataProvider userNameAndPasswordProvider
     */
    public function testRecipeMembersUrls($name, $pass)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => $name,
            'PHP_AUTH_PW' => $pass,
        ]);
        $crawler = $this->client->getResponse();
        $searchText = 'Drinks Index';


        // Act
        $client->request('GET', '/recipe/');
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

    public function testSearchForDrink()
    {
        // Arrange
        $buttonName = 'btn_submit';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->followRedirects(true);
        $client->request('GET', '/recipe/search');
        $expectedContent = 'Search Results';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request('GET','/recipe/search')->selectButton($buttonName)->form([
            'q'  => 'Bacardi',
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);
        //$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

    }

    /**
     * @dataProvider recipeRequestToBePublicDataProvider
     */
    public function testRecipeRequestsToBePublic($url)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->request('GET', $url);
        $client->followRedirect();

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function recipeRequestToBePublicDataProvider()
    {
        return [
            ['/recipe/' .self::ID . '/request'],
            ['/recipe/' .self::ID . '/reject'],
            ['/recipe/' .self::ID . '/publish'],
        ];
    }
    public function recipeDataProvider()
    {
        return [
          ['/recipe/', 'Drinks Index'],
          ['/recipe/' . self::ID . '/edit', 'Edit Drink'],
          ['/recipe/' . self::ID, 'Price Range'],
        ];
    }

    /**
     * @dataProvider recipeDataProvider
     */
    public function testRecipeLinksWithAdmin($url, $text)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $searchText = $text;


        // Act
        $client->request('GET', $url);
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

    /**
     * @dataProvider recipeDataProvider
     */
    public function testRecipeLinksWithUser($url, $text)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'joe_user',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $searchText = $text;


        // Act
        $client->request('GET', $url);
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

    public function testSearchByDateRange()
    {
        // Arrange
        $buttonName = 'btn_submit';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->followRedirects(true);
        $client->request('GET', '/recipe/');
        $expectedContent = 'Drinks for Date Range';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request('GET','/recipe/')->selectButton($buttonName)->form([
            'date1'  => '2017-04-05',
            'date2'  => '2018-04-17',
        ]));
        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    public function testRecipeDelete()
    {
        // Arrange
        $url = '/recipe/' . self::DELETE_ID;
        $httpMethod = 'GET';
        $client = static::createClient();
        $buttonName = '_method';
        $searchText = 'Rum';
        $linkText = 'Delete';

        // Act
        $crawler = $client->request($httpMethod, $url);
        //$link = $crawler->selectLink($linkText)->link();
       // $client->click($link);
        $content = $client->getResponse()->getContent();

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert
        $this->assertContains($searchTextLowerCase, $contentLowerCase);

        $client->followRedirects(true);
        //$client->request('GET', '/');
        $expectedContent = 'Rum';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request($httpMethod,$url)->selectButton($buttonName)->form());
        //$client->submit($crawler->filter('#delete')->form());

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);
    }


//    public function testRecipeDelete()
//    {
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'derek',
//            'PHP_AUTH_PW' => 'pass',
//        ]);
//        $expectedContent = 'Rum';
//        $expectedContentlowercase = strtolower($expectedContent);
//
//
//        $client->followRedirects(true);
//        $crawler = $client->request('GET', '/recipe/' . self::DELETE_ID);
//        $client->submit($crawler->filter('#delete')->form());
//
//        $post = $client->getContainer()->get('doctrine')->getRepository(Recipe::class)->find(1);
//        $this->assertNull($post);
//
//
//        // to lowercase
//        $content = $client->getResponse()->getContent();
//        $contentlowercase = strtolower($content);
//
//        // Assert
//        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
//        $this->assertContains($expectedContentlowercase,$contentlowercase);
//    }
//    public function testShowRecipePage()
//    {
//        // Arrange
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'derek',
//            'PHP_AUTH_PW' => 'pass',
//        ]);
//
//        // Expect exception - BEFORE you Act!
//        $this->expectException(NotFoundHttpException::class);
//
//        // Act
//        $client->request('GET', 'recipe/' . self::ID . '/edit');
//
//        $review = new Review();
//        $recipe = new Recipe();
//        $recipe->setImage(null);
//        $recipe->addReview($review);
//        $recipe->removeReview($review);
//
//        // Assert
//        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
//    }

}