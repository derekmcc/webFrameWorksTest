<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:52
 */

namespace App\Tests\Controller;

use App\Entity\Recipe;
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
    const ID = '46';

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

//    public function testEditRecipe()
//    {
//        // Arrange
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'derek',
//            'PHP_AUTH_PW' => 'pass',
//        ]);
//        $searchText = 'Edit Drink';
//
//
//        // Act
//        $client->request('GET', '/recipe/1/edit');
//        $content = $client->getResponse()->getContent();
//
//        // Assert
//        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
//
//        // to lower case
//        $searchTextLowerCase = strtolower($searchText);
//        $contentLowerCase = strtolower($content);
//
//        // Assert
//        $this->assertContains(
//            $searchTextLowerCase,
//            $contentLowerCase
//        );
//    }

//    public function testNewRecipe()
//    {
//        // Arrange
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'derek',
//            'PHP_AUTH_PW' => 'pass',
//        ]);
//        $searchText = 'New Drink';
//
//
//        // Act
//        $client->request('GET', '/recipe/new');
//        $content = $client->getResponse()->getContent();
//
//        $user = new User();
//        $recipe = new Recipe();
//        $file = $recipe->getImage();
//        $recipe->setImage($file);
//        $recipe->setIsPublic(false);
//        $recipe->setAuthor($user);
//        $recipe->setRequestRecipePublic(false);
//        $recipe->setSummary('fasfas');
//        $recipe->setDescription('asdfsaf');
//        $recipe->setPrice(1);
//        $recipe->setPublishedAt(new \DateTime('now'));
//        $recipe->setTitle('dsafsa');
//        $recipe->setIngredients('dfsafs');
//
//        //$client->followRedirect();
//
//        // Assert
//        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
//
//        // to lower case
//        $searchTextLowerCase = strtolower($searchText);
//        $contentLowerCase = strtolower($content);
//
//        // Assert
//       // $this->assertContains(
//         //   $searchTextLowerCase,
//           // $contentLowerCase
//        //);
//    }

//    public function testAdminHomePage()
//    {
//        // Arrange
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'derek',
//            'PHP_AUTH_PW' => 'pass',
//        ]);
//
//        // Act
//        $client->request('GET', '/admin');
//
//        // Assert
//        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
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
//        $client->request('GET', '/recipe/0');
//
//        // Assert
//        $this->fail("Expected exception {NotFoundHttpException::class}");
//    }

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

//    public function testRecipeDelete()
//    {
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'derek',
//            'PHP_AUTH_PW' => 'pass',
//        ]);
//        $crawler = $client->request('GET', '/recipe/' . self::ID);
//        $client->submit($crawler->filter('#delete')->form());
//
//        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
//
//        $post = $client->getContainer()->get('doctrine')->getRepository(Recipe::class)->find(1);
//        $this->assertNull($post);
//        $client->followRedirect();
//        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
//    }
}