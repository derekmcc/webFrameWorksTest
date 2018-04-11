<?php


namespace App\Tests\Form;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RecipeTypeTest extends WebTestCase
{

    const ID = '46';

    protected function setUp()
    {
        parent::setUp();
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'derek',
            'PHP_AUTH_PW' => 'pass',
        ]);
    }

    /**
     * @dataProvider recipeDetailsProvider
     */
    public function testAddAndEditDrinkThroughForm($url,$pageContent,$httpMethod,$title,$description,$summary,$ingredients,$price,$requestPublic,$image)
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
            'recipe[title]'  => $title,
            'recipe[image]'  => $image,
            'recipe[summary]'  => $summary,
            'recipe[description]'  => $description,
            'recipe[ingredients]'  => $ingredients,
            'recipe[price]'  => $price,
            'recipe[requestRecipePublic]'  => $requestPublic,
        ]));

        // to lowercase
        $content = $client->getResponse()->getContent();
        $contentlowercase = strtolower($content);

        // Assert
        $this->assertContains($expectedContentlowercase,$contentlowercase);

   }

   public function recipeDetailsProvider()
   {
       return [
            ['/recipe/new', 'Drinks Index', 'GET', 'New Rum', 'Award winning Rum', 'white rum', 'ingredients, etc', '€11-20', false, new UploadedFile(
                'public/uploads/images/10Cane.jpg',
                '10Cane.jpg',
                'image/jpeg',123)],
           ['/recipe/' . self::ID . '/edit', 'Drink Details', 'GET', 'Old Rum', 'Award winning Rum', 'white rum', 'ingredients, etc', '€21-30', true, new UploadedFile(
               'public/uploads/images/10Cane.jpg',
               '10Cane.jpg',
               'image/jpeg',123)],
       ];
   }
}