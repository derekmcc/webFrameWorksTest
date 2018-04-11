<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:53
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }
    public function testUserLoginWithValidData()
    {
        // Arrange
        $url = '/';
        $httpMethod = 'GET';
        $client = static::createClient();
        $buttonName = 'btn_submit';
        $searchText = 'User Login';
        $linkText = 'Login';

        // Act
        $crawler = $client->request($httpMethod, $url);
        $link = $crawler->selectLink($linkText)->link();
        $client->click($link);
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
        $client->submit($client->request($httpMethod,'/login')->selectButton($buttonName)->form([
            '_username'  => 'derek',
            '_password'  => 'pass',
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    public function testRegularUserCantAccessAdmin()
    {
        // Arrange
        $url = '/';
        $httpMethod = 'GET';
        $client = static::createClient();
        $buttonName = 'btn_submit';
        $searchText = 'Page not found';
        $linkText = 'Login';


        // Act
        $crawler = $client->request($httpMethod, $url);
        $link = $crawler->selectLink($linkText)->link();
        $client->click($link);

        $client->followRedirects(true);

        $client->submit($client->request($httpMethod,'/login')->selectButton($buttonName)->form([
            '_username'  => 'john_user',
            '_password'  => 'pass',
        ]));


        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Act 2
        $client->request($httpMethod, '/admin');
        $content = $client->getResponse()->getContent();

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert 2
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());

        // Assert 3
        $this->assertContains(
            $searchTextLowerCase,
            $contentLowerCase
        );
    }
}