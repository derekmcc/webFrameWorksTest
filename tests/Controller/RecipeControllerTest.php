<?php

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
            ['/recipe/showRecipe', 'Drink Results'],
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

    /**
 * @dataProvider userNameProvider
 */
    public function testSearchByDateRange($username)
    {
        // Arrange
        $buttonName = 'btn_submit';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => $username,
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->followRedirects(true);
        $client->request('GET', '/recipe/');
        $expectedContent = 'Drink Results';
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

    public function userNameProvider()
    {
        return [
            ['derek'],
            ['john_user'],
        ];
    }

    public function testSearchByDateRangeForPublic()
    {
        // Arrange
        $buttonName = 'btn_submit';


        // Act
        $this->client->followRedirects(true);
        $this->client->request('GET', '/recipe/');
        $expectedContent = 'Drink Results';
        $expectedContentlowercase = strtolower($expectedContent);
        $this->client->submit($this->client->request('GET','/recipe/')->selectButton($buttonName)->form([
            'date1'  => '2017-04-05',
            'date2'  => '2018-04-17',
        ]));
        // to lowercase
        $content = $this->client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    /**
     * @dataProvider priceRangeProvider
     */
    public function testSortByPriceRange($priceRange, $username)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => $username,
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->followRedirects(true);
        $client->request('GET', '/recipe/');
        $expectedContent = 'Drink Results';
        $expectedContentlowercase = strtolower($expectedContent);

        $crawler = $client->request('GET', '/recipe/');
        $link = $crawler->selectLink($priceRange)->link();
        $client->click($link);

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    /**
     * @dataProvider priceRangePublicProvider
     */
    public function testSortByPriceRangeForPublic ($priceRange)
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->followRedirects(true);
        $client->request('GET', '/recipe/');
        $expectedContent = 'Drink Results';
        $expectedContentlowercase = strtolower($expectedContent);

        $crawler = $client->request('GET', '/recipe/');
        $link = $crawler->selectLink($priceRange)->link();
        $client->click($link);

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    public function priceRangePublicProvider()
    {
        return [
            ['Drinks under €10'],
            ['Drinks between €11-20'],
            ['Drinks between €21-30'],
            ['Drinks between €31-40'],
            ['Drinks over €40'],
        ];
    }

    public function priceRangeProvider()
    {
        return [
            ['Drinks under €10','john_user'],
            ['Drinks between €11-20', 'john_user'],
            ['Drinks between €21-30', 'derek'],
            ['Drinks between €31-40', 'derek'],
            ['Drinks over €40', 'derek'],
        ];
    }

    /**
     * @param $id
     * @dataProvider recipeDeleteProvider
     */
    public function testRecipeDelete($id)
    {
        // Arrange
        $buttonName = 'btn_delete';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $url = '/recipe/' . $id;
        $httpMethod = 'GET';

        // Act
        $client->followRedirects(true);
        $client->request($httpMethod, $url);
        $expectedContent = 'Drinks Index';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request($httpMethod,$url)->selectButton($buttonName)->form());

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);
    }

    public function recipeDeleteProvider()
    {
        return array(
            [17],
            [19],
        );
    }
}