<?php

namespace App\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
class SimpleTest extends WebTestCase
{



    /**
     *

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
     *   public function testRecipeDelete()
    {
    $client = static::createClient([], [
    'PHP_AUTH_USER' => 'derek',
    'PHP_AUTH_PW' => 'pass',
    ]);
    $expectedContent = 'Rum';
    $expectedContentlowercase = strtolower($expectedContent);


    $client->followRedirects(true);
    $crawler = $client->request('GET', '/recipe/' . self::DELETE_ID);
    $client->submit($crawler->filter('#delete')->form());

    $post = $client->getContainer()->get('doctrine')->getRepository(Recipe::class)->find(1);
    $this->assertNull($post);


    // to lowercase
    $content = $client->getResponse()->getContent();
    $contentlowercase = strtolower($content);

    // Assert
    $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    $this->assertContains($expectedContentlowercase,$contentlowercase);
    }
    public function testReviewDelete()
    {
    // Arrange
    $url = '/review/' . self::DELETE_ID;
    $httpMethod = 'GET';
    $client = static::createClient();
    $buttonName = 'btn_submit';
    $searchText = 'Review Details';
    $linkText = 'Delete Review';

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
    $expectedContent = 'Review Index';
    $expectedContentlowercase = strtolower($expectedContent);
    $client->submit($client->request($httpMethod,$url)->selectButton($buttonName)->form());
    //$client->submit($crawler->filter('#delete')->form());

    // to lowercase
    $content = $client->getResponse()->getContent();
    $contentlowercase = strtolower($content);

    // Assert
    $this->assertContains($expectedContentlowercase,$contentlowercase);
    }     *    ///////////////////////////////////////////////////////////////////////////
    //    public function testNewReview()
    //    {
    //        // Arrange
    //        $client = static::createClient([], [
    //            'PHP_AUTH_USER' => 'derek',
    //            'PHP_AUTH_PW' => 'pass',
    //        ]);
    //        $searchText = 'New Review';
    //       // $id=90;
    //        // Act
    //        $client->request('GET', '/review/new/1');
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
    //         $this->assertContains(
    //           $searchTextLowerCase,
    //         $contentLowerCase
    //        );
    //    }
    //
    //    public function testEditReview()
    //    {
    //        // Arrange
    //        $client = static::createClient([], [
    //            'PHP_AUTH_USER' => 'derek',
    //            'PHP_AUTH_PW' => 'pass',
    //        ]);
    //
    //        // Act
    //        $client->request('GET', '/review/1/edit');
    //
    //        // Assert
    //        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    //    }
     * //    public function testAddNewUser()
    //    {
    //        $client = static::createClient([], [
    //            'PHP_AUTH_USER' => 'derek',
    //            'PHP_AUTH_PW' => 'pass',
    //        ]);
    //
    //        $crawler = $client->request('GET', '/user/');
    //
    //        $crawler = $client->click($crawler->selectLink('Signup')->link());
    //
    //        $form = $crawler->selectButton('Save')->form(array(
    //            'user[firstname]'       => 'Another Test User',
    //            'user[username]'   => 'another_test_user',
    //            'user[email]'      => 'test@anotheruser.com',
    //            'user[surname]'   => 'another_test_user',
    //            'user[password]'      => 'pass',
    //        ));
    //        $crawler = $client->submit($form);
    //        $crawler = $client->followRedirect();
    //
    //        $this->assertGreaterThan(
    //            0,
    //            $crawler->filter('html:contains("Login")')->count()
    //        );
    //    }
     *
    //    public function testReviewFormAdd()
    //    {
    //        $recipe = new Recipe();
    //        // Arrange
    //       // $recipe = new Recipe();
    //        $url = '/review/new/' . $recipe->getId();
    //        $httpMethod = 'GET';
    //        $client = static::createClient([], [
    //            'PHP_AUTH_USER' => 'derek',
    //            'PHP_AUTH_PW' => 'pass',
    //        ]);
    //        $user = new User();
    //        $review = new Review();
    //      //  $recipe = new Recipe();
    //        $review->setAuthor($user);
    //        $review->setSummary('Summary');
    //        $review->setPublishedAt(new \DateTime('now'));
    //        $review->setRetailers('Lidl, Supervalue, M&S');
    //        $review->setPrice(35.99);
    //        $review->setStars(4);
    //        $review->setIsPublicReview(true);
    //        $review->setImage('/images/image.png');
    //        $review->setRequestReviewPublic(false);
    //        $review->setRecipe($recipe);
    //
    //        // $title = 'Bacardi Añejo';
    //        $image1 = 'image.jpeg';
    //        $image2 = 'image.jpeg';
    //        $summary = 'Summary';
    //        $price = '€11-20';
    //        $stars = 4;
    //        $retailers = 'Lidl, Supervalue, M&S';
    //        $buttonName = 'Save';
    //
    //        // Act
    //        $crawler = $client->request($httpMethod, $url);
    //
    //        $buttonCrawlerNode = $crawler->selectButton($buttonName);
    //        $form = $buttonCrawlerNode->form();
    //
    //        // submit the form with data
    //        $client->submit($form, [
    //            'review[retailers]'  => $retailers,
    //            'review[image1]'  => $image1,
    //            'review[image2]'  => $image2,
    //            'review[summary]'  => $summary,
    //            'review[price]'  => $price,
    //            'review[stars]'  => $stars,
    //        ]);
    //        $user = new User();
    //        $review = new Review();
    //        $recipe = new Recipe();
    //        $review->setAuthor($user);
    //        $review->setSummary('Summary');
    //        $review->setPublishedAt(new \DateTime('now'));
    //        $review->setRetailers('Lidl, Supervalue, M&S');
    //        $review->setPrice($price);
    //        $review->setStars(4);
    //        $review->setIsPublicReview(true);
    //        $review->setImage($image1);
    //        $review->setRequestReviewPublic(false);
    //        $review->setRecipe($recipe->getId());
    //        // $content = $client->getResponse()->getContent();
    //        //$client->followRedirect();
    //        $crawler = $client->getResponse();
    //    //    echo $crawler;
    //        // Assert
    //        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    //        // $this->assertSame('/recipe/index', $client->getResponse()->getStatusCode());
    //
    //        $recipe = $client->getContainer()->get('doctrine')->getRepository(Recipe::class)->findOneBy([
    //            'summary' => $summary,
    //        ]);
    //
    //        $this->assertNotNull($recipe);
    //        $this->assertSame($summary, $recipe->getSummary());
    //        $this->assertSame($image, $recipe->getImage());
    //        $this->assertSame($description, $recipe->getDescription());
    //        $this->assertSame($ingredients, $recipe->getIngredients());
    //        $this->assertSame($price, $recipe->getPrice());
    //        $this->assertSame($requestPublic, $recipe->getRequestIsPublic());
    //    }
    //    public function testNewComment()
    //    {
    //        $client = static::createClient([], [
    //            'PHP_AUTH_USER' => 'username',
    //            'PHP_AUTH_PW' => 'pass',
    //        ]);
    //        $client->followRedirects();
    //
    //        // Find first blog post
    //        $crawler = $client->request('GET', 'review/new');
    //       // $postLink = $crawler->filter('article.post > h2 a')->link();
    //
    //        //$crawler = $client->click($postLink);
    //
    //        $form = $crawler->selectButton('Publish comment')->form([
    //            'comment[content]' => 'Hi, Symfony!',
    //        ]);
    //        $crawler = $client->submit($form);
    //
    //        $newComment = $crawler->filter('.post-comment')->first()->filter('div > p')->text();
    //
    //        $this->assertSame('Hi, Symfony!', $newComment);
    //    }
     *
     *         // submit the form with data
    //        $form = $crawler->selectButton('Save')->form([
    //            'recipe[title]'  => 'tester',
    //            'recipe[image]'  => 'sdfa',
    //            'recipe[summary]'  => 'sdfas',
    //            'recipe[description]'  => 'sasfsf',
    //            'recipe[ingredients]'  => 'aaa',
    //            'recipe[price]'  => '€11-20',
    //            'recipe[requestRecipePublic]'  => true,
    //        ]);
    //        $user = new User();
    //        $user->setUsername("fred");
    //        $user->setEmail('asdfs@afas.com');
    //        $user->setPassword('abd');
    //        $user->setFirstname('asfsa');
    //        $recipe = new Recipe();
    //        $file = $recipe->getImage();
    //        $recipe->setImage($file);
    //        $recipe->setIsPublic(false);
    //        $recipe->setAuthor($user);
    //        $recipe->setRequestRecipePublic(true);
    //        $recipe->setSummary('sdfas');
    //        $recipe->setDescription('sasfsf');
    //        $recipe->setPrice('€11-20');
    //        $recipe->setPublishedAt(new \DateTime('now'));
    //        $recipe->setTitle('test');
    //        $recipe->setIngredients('aaa');
    //        $crawler =$client->submit($form);
     *
     *        // $this->assertSame('/recipe/index', $client->getResponse()->getStatusCode());
    //
    //        $recipe = $client->getContainer()->get('doctrine')->getRepository(Recipe::class)->findOneBy([
    //            'title' => $title,
    //        ]);
    //
    //        $this->assertNotNull($recipe);
    //        $this->assertSame($summary, $recipe->getSummary());
    //        $this->assertSame($image, $recipe->getImage());
    //        $this->assertSame($description, $recipe->getDescription());
    //        $this->assertSame($ingredients, $recipe->getIngredients());
    //        $this->assertSame($price, $recipe->getPrice());
    //        $this->assertSame($requestPublic, $recipe->getRequestIsPublic());
     *
     *
    //    protected function createProgrammer(array $data)
    //    {
    //        $data = array_merge(array(
    //            'powerLevel' => rand(0, 10),
    //            'user' => $this->getEntityManager()
    //                ->getRepository('AppBundle:User')
    //                ->findAny()
    //        ), $data);
    //
    //        $accessor = PropertyAccess::createPropertyAccessor();
    //        $programmer = new Recipe();
    //        foreach ($data as $key => $value) {
    //            $accessor->setValue($programmer, $key, $value);
    //        }
    //
    //
    //        $this->getEntityManager()->persist($programmer);
    //        $this->getEntityManager()->flush();
    //        return $programmer;
    //
    //    }
    //    public function testGETProgrammer()
    //    {
    //        $this->createProgrammer(array(
    //            'nickname' => 'UnitTester',
    //            'avatarNumber' => 3,
    //        ));
    //
    //        $response = $this->client->get('/recipe/new');
    //        $this->assertEquals(200, $response->getStatusCode());
    //        $data = $response->json();
    //        $this->assertEquals(array(
    //            'nickname',
    //            'avatarNumber',
    //            'powerLevel',
    //            'tagLine'
    //        ), array_keys($data));
    //    }
     * //    public function testSetValuesAndSubmitFormInOneGo()
    //    {
    //        // Arrange
    //        $url = '/recipe/new';
    //        $httpMethod = 'GET';
    //        $client = static::createClient([], [
    //            'PHP_AUTH_USER' => 'derek',
    //            'PHP_AUTH_PW' => 'pass',
    //        ]);
    //
    //       // $title = 'Bacardi Añejo';
    //        $title = 'Tester';
    //        $image = 'image.jpeg';
    //        $description = 'Rum';
    //        $summary = 'Ubi est audax amicitia.';
    //        $ingredients = 'ingredients, etc';
    //        $price = '€11-20';
    //        $requestPublic = false;
    //        $buttonName = 'Save';
    //
    //        // Act
    //        $crawler = $client->request($httpMethod, $url);
    //
    //        $buttonCrawlerNode = $crawler->selectButton($buttonName);
    //        $form = $buttonCrawlerNode->form();
    //
    //        // submit the form with data
    //        $client->submit($form, [
    //            'recipe[title]'  => $title,
    //            'recipe[image]'  => $image,
    //            'recipe[summary]'  => $summary,
    //            'recipe[description]'  => $description,
    //            'recipe[ingredients]'  => $ingredients,
    //            'recipe[price]'  => $price,
    //            'recipe[requestRecipePublic]'  => $requestPublic,
    //        ]);
    //
    //
    //       // $content = $client->getResponse()->getContent();
    //        //$client->followRedirect();
    //        $crawler = $client->getResponse();
    //
    //        // Assert
    //        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    //       // $this->assertSame('/recipe/index', $client->getResponse()->getStatusCode());
    //
    //        $recipe = $client->getContainer()->get('doctrine')->getRepository(Recipe::class)->findOneBy([
    //            'title' => $title,
    //        ]);
    //
    //        $this->assertNotNull($recipe);
    //        $this->assertSame($summary, $recipe->getSummary());
    //        $this->assertSame($image, $recipe->getImage());
    //        $this->assertSame($description, $recipe->getDescription());
    //        $this->assertSame($ingredients, $recipe->getIngredients());
    //        $this->assertSame($price, $recipe->getPrice());
    //        $this->assertSame($requestPublic, $recipe->getRequestIsPublic());
    //    }
    ////
    ////    public function testUsereditAction()
    ////    {
    ////        $client = static::createClient([], [
    ////            'PHP_AUTH_USER' => 'derek',
    ////            'PHP_AUTH_PW' => 'pass',
    ////        ]);
    ////        $crawler = $client->request('GET', '/recipe/new');
    ////        $this->assertTrue($client->getResponse()->isSuccessful());
    ////
    ////        $form = $crawler->selectButton('Save')->form();
    ////        $form->get('recipe[title]')->setValue('testTitle');
    ////        $form->get('recipe[image]')->setValue('testImage');
    ////        $form->get('recipe[summary]')->setValue('testTitle');
    ////        $form->get('recipe[description]')->setValue('testImage');
    ////        $form->get('recipe[price]')->setValue('€11-20');
    ////        $form->get('recipe[requestRecipePublic]')->setValue(false);
    ////        $crawler = $client->submit($form);
    ////        $response = $client->getResponse();
    ////
    ////        $this->assertEquals(302, $response->getStatusCode());
    ////        $client->followRedirect();
    ////        $this->assertTrue($response->isRedirect('/recipes/'));
    ////
    ////        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    ////    }
     */
}