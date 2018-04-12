<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 12/04/2018
 * Time: 18:10
 */

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserTypeTest extends WebTestCase
{
    const ID = '18';
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider userDetailsProvider
     */
    public function testAddAndEditUserThroughForm($url,$pageContent,$httpMethod)
    {
        // Arrange
        $buttonName = 'btn_submit';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        // Act
        $client->followRedirects(true);
        $client->request($httpMethod, $url);
        $expectedContent = $pageContent;
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request($httpMethod,$url)->selectButton($buttonName)->form([
            'user[username]'  => 'freddy',
            'user[plainPassword][first]'  => 'pass',
            'user[plainPassword][second]'  => 'pass',
            'user[firstname]'  => 'Fred',
            'user[surname]'  => 'abc',
            'user[email]' => 'freddy@abc.com',
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    public function userDetailsProvider()
    {
        return [
            ['/user/'.self::ID.'/edit','Profile', 'GET'],
        ];
    }
}