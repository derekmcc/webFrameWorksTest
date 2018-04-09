<?php


namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Recipe;
class RecipeTypeTest extends WebTestCase
{
    public function testSetValuesAndSubmitFormInOneGo()
    {
        // Arrange
        $url = '/recipe/new';
        $httpMethod = 'GET';
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);

        $title = 'Bacardi Añejo';
       // $title = 'Tester';
        $image = 'image.jpeg';
        $description = 'Rum';
        $summary = 'Ubi est audax amicitia.';
        $ingredients = 'ingredients, etc';
        $price = '€11-20';
        $requestPublic = false;
        $buttonName = 'Save';

        // Act
        $crawler = $client->request($httpMethod, $url);

        $buttonCrawlerNode = $crawler->selectButton($buttonName);
        $form = $buttonCrawlerNode->form();

        // submit the form with data
        $client->submit($form, [
            'recipe[title]'  => $title,
            'recipe[image]'  => $image,
            'recipe[summary]'  => $summary,
            'recipe[description]'  => $description,
            'recipe[ingredients]'  => $ingredients,
            'recipe[price]'  => $price,
            'recipe[requestRecipePublic]'  => $requestPublic,
        ]);

       // $content = $client->getResponse()->getContent();
        //$client->followRedirect();
        $crawler = $client->getResponse();

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
       // $this->assertSame('/recipe/index', $client->getResponse()->getStatusCode());

        $recipe = $client->getContainer()->get('doctrine')->getRepository(Recipe::class)->findOneBy([
            'title' => $title,
        ]);

        $this->assertNotNull($recipe);
        $this->assertSame($summary, $recipe->getSummary());
//        $this->assertSame($image, $recipe->getImage());
//        $this->assertSame($description, $recipe->getDescription());
//        $this->assertSame($ingredients, $recipe->getIngredients());
//        $this->assertSame($price, $recipe->getPrice());
//        $this->assertSame($requestPublic, $recipe->getRequestIsPublic());
    }
}