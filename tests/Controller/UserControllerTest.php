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