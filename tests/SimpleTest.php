<?php

namespace App\Test;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testOnePlusOneEqualsTwo()
    {
        // Arrange
        $num1 = 1;
        $num2 = 1; $expectedResult = 2;

        // Act
        $result = $num1 + $num2;

        // Assert
        $this->assertEquals($expectedResult, $result);
    }
    /**
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