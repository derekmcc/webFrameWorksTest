<?php


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
    const DELETE_ID1 = '15';
    const DELETE_ID2 = '16';

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
            'user[username]'  => 'bart_user',
            'user[plainPassword][first]'  => 'pass',
            'user[plainPassword][second]'  => 'pass',
            'user[firstname]'  => 'Bart',
            'user[surname]'  => 'Simpson',
            'user[email]' => 'bart@example.com'
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }


    public function userDeleteProvider()
    {
        return array(
            [self::DELETE_ID1, 'username10'],
            [self::DELETE_ID2, 'derek'],
        );
    }

    /**
     * Needs to delete client so get that ID----------------
     * @dataProvider userDeleteProvider
     */
    public function testUserDelete($id, $username)
    {
        // Arrange
        $client = static::createClient([], [
            'PHP_AUTH_USER' => $username,
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        //$client->followRedirects(true);
        $client->submit($client->request('GET','/user/' . $id)->selectButton('btn_delete')->form());

        // Assert
        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        // Arrange
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->find($id);

        // Assert
        $this->assertNull($user);
    }
}