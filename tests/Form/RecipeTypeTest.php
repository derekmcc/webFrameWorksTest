<?php


namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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
        $title = 'Bacardi';
        $image = 'image.jpeg';
        $description = 'Rum';
        $summary = 'New rum';
        $ingredients = 'ingredients, etc';
        $price = 10-20;
        $requestPublic = false;

        $buttonName = 'Save';

        // Act
        $crawler = $client->request($httpMethod, $url);

        $buttonCrawlerNode = $crawler->selectButton($buttonName);
        $form = $buttonCrawlerNode->form();

        // submit the form with data
        $client->submit($form, [
            'recipe_title'  => $title,
            'recipe_image'  => $image,
            'recipe_summary'  => $summary,
            'recipe_description'  => $description,
            'recipe_ingredients'  => $ingredients,
            'recipe_price'  => $price,
            'recipe_requestRecipePublic'  => $requestPublic,
        ]);

        $content = $client->getResponse()->getContent();

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

    }
}