<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 09/04/2018
 * Time: 23:11
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Review;
use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $client = null;
    const ID = '1';
    const DELETE_ID = '15';

    public function setUp()
    {
        $this->client = static::createClient();
    }
    /**
     * @dataProvider userUrlProvider
     */
    public function testUserUrls($url, $text)
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

    public function userUrlProvider()
    {
        return array(
            ['/user/', 'User index'],
            ['/user/' . self::ID, 'Profile'],
            ['/user/account', 'Profile'],
        );
    }

    public function testSignUpForNewUserAccount()
    {
        // Arrange
        $url = '/';
        $httpMethod = 'GET';
        $client = static::createClient();
        $buttonName = 'btn_submit';
        $searchText = 'Sign up';
        $linkText = 'Signup';

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
        $expectedContent = 'User Login';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request($httpMethod,'/user/new')->selectButton($buttonName)->form([
            'user[username]'  => 'fred_user',
            'user[plainPassword][first]'  => 'pass',
            'user[plainPassword][second]'  => 'pass',
            'user[firstname]'  => 'Fred',
            'user[surname]'  => 'User',
            'user[email]' => 'fred@example.com'
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    public function testUserDelete()
    {
        // Arrange
        $url = '/user/' . self::DELETE_ID;
        $httpMethod = 'GET';
        $client = static::createClient();
        $buttonName = 'btn_submit';
        $searchText = 'Profile';
        $linkText = 'Delete User Account';

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
        $expectedContent = 'User Index';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request($httpMethod,$url)->selectButton($buttonName)->form());
        //$client->submit($crawler->filter('#delete')->form());

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);
    }

//    public function testAddNewUser()
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

}