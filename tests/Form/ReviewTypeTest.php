<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReviewTypeTest extends WebTestCase
{

    const ID = '11';

    protected function setUp()
    {
        parent::setUp();
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
    }

    public function testAddNewReview()
    {
        // Arrange
        $url = '/recipe/' . self::ID;
        $urlForForm = '/review/new/' . self::ID;
        $httpMethod = 'GET';
        $buttonName = 'btn_submit';
        $searchText = 'new review';
        $linkText = 'Add New Review';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

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
        //$client->request($httpMethod, $url);
        $expectedContent = 'Drink Details';
        $expectedContentlowercase = strtolower($expectedContent);
        $client->submit($client->request($httpMethod,$urlForForm)->selectButton($buttonName)->form([
            'review[summary]'  => 'Good Rum',
            'review[image]'  => new UploadedFile(
                'public/uploads/images/10Cane.jpg',
                '10Cane.jpg',
                'image/jpeg',123),
            'review[retailers]'  => 'M&S, Supervale, Tesco',
            'review[stars]'  => 4.5,
            'review[price]'  => 19.99,
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);
    }

    /**
     * @dataProvider reviewDetailsProvider
     */
    public function testEditUserDetails($url,$pageContent,$httpMethod)
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
            'review[summary]'  => 'Ok Rum',
            'review[image]'  => new UploadedFile(
                'public/uploads/images/10Cane.jpg',
                '10Cane.jpg',
                'image/jpeg',123),
            'review[retailers]'  => 'Lidl, Aldi, Tesco',
            'review[stars]'  => 2.5,
            'review[price]'  => 29.99,
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

    }

    public function reviewDetailsProvider()
    {
        return [
            ['/review/'.self::ID.'/edit','Review Details', 'GET'],
        ];
    }
}