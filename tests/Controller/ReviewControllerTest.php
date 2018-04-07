<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:53
 */

namespace App\Controller\Test;

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
    public function testNewComment()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'username',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $client->followRedirects();

        // Find first blog post
        $crawler = $client->request('GET', 'review/new');
       // $postLink = $crawler->filter('article.post > h2 a')->link();

        //$crawler = $client->click($postLink);

        $form = $crawler->selectButton('Publish comment')->form([
            'comment[content]' => 'Hi, Symfony!',
        ]);
        $crawler = $client->submit($form);

        $newComment = $crawler->filter('.post-comment')->first()->filter('div > p')->text();

        $this->assertSame('Hi, Symfony!', $newComment);
    }
//    public function testNewReview()
//    {
//        // Arrange
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'derek',
//            'PHP_AUTH_PW' => 'pass',
//        ]);
//        $searchText = 'New Drink';
//
//        // Act
//        $client->request('GET', '/review/new');
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

    public function publicReviewUrls()
    {
        return array(
            ['/review/']
        );
    }

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
}