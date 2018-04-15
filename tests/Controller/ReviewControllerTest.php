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
    const ID = '34';

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider reviewUrlProvider
     */
    public function testReviewPublicUrls($url, $content)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $this->client->request('GET',$url);
        $crawler = $this->client->getResponse();
        $searchText = $content;

        // Act
        $statusCode = $this->client->getResponse()->getStatusCode();

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

    /**
     * @dataProvider userNameAndPasswordProvider
     */
    public function testReviewMembersUrls($name, $pass)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => $name,
            'PHP_AUTH_PW' => $pass,
        ]);
        $crawler = $this->client->getResponse();
        $searchText = 'Review Index';


        // Act
        $client->request('GET', '/review/');
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

    public function reviewUrlProvider()
    {
        return array(
            ['/review/', 'Review Index', ],
            ['/review/' . self::ID, 'Review Details'],
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
     * @dataProvider reviewRequestToBePublicDataProvider
     */
    public function testReviewRequestsToBePublic($url)
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

    public function reviewRequestToBePublicDataProvider()
    {
        return [
            ['/review/' .self::ID . '/request'],
            ['/review/' .self::ID . '/reject'],
            ['/review/' .self::ID . '/publish'],
            ['/review/' . self::ID .'/upVote'],
            ['/review/' . self::ID .'/downVote'],
        ];
    }


    /**
     * @dataProvider reviewDeleteProvider
     */
    public function testReviewDelete($id)
    {
        // Arrange
        $buttonName = 'btn_delete';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $url = '/review/' . $id;
        $httpMethod = 'GET';

        // Act
        $client->followRedirects(true);
        $client->request($httpMethod, $url);
        $expectedContent = 'Review Index';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request($httpMethod,$url)->selectButton($buttonName)->form());

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);
    }

    public function reviewDeleteProvider()
    {
        return array(
            [40],
            [41],
            [42],
        );
    }

}