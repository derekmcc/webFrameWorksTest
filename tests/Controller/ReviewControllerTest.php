<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:53
 */

namespace App\Controller\Test;

use App\Entity\User;
use App\Entity\Review;
use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReviewControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider publicReviewUrls
     */
    public function testReviewPublicUrls($url)
    {
        // Arrange
        $this->client->request('GET',$url);
        $crawler = $this->client->getResponse();

        // Act
        $statusCode = $this->client->getResponse()->getStatusCode();

        // Assert
        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }
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
    public function testNewReview()
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $searchText = 'New Review';
       // $id=90;
        // Act
        $client->request('GET', '/review/new/1');
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

    public function publicReviewUrls()
    {
        return array(
            ['/review/']
        );
    }

    public function testEditReview()
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->request('GET', '/review/1/edit');

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

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
}