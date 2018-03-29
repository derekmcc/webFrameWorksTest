<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 26/03/2018
 * Time: 15:23
 */

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadData extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadRecipes($manager);
       // $this->loadReviews($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$username, $password, $roles]) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->encodePassword($user, $password));
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }
/*
    private function loadReviews(ObjectManager $manager)
    {
        foreach ($this->getReviewData() as $index => $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $this->addReference('tag-'.$name, $tag);
        }

        $manager->flush();
    }*/

    private function loadRecipes(ObjectManager $manager)
    {
        foreach ($this->getRecipeData() as [$title, $summary, $description, $image, $author, $ingredients, $price, $public]) {
            $recipe = new Recipe();
            $recipe->setTitle($title);
            $recipe->setSummary($this->getRandomText());
            $recipe->setDescription($description);
            $recipe->setImage($image);
            $recipe->setAuthor($author);
            $recipe->setIngredients($ingredients);
            $recipe->setPrice($price);
            $recipe->setIsPublic($public);

            $manager->persist($recipe);

            foreach (range(1, 5) as $i) {
                $review = new Review();
                $review->setAuthor($this->getReference('john_user'));
                $review->setSummary($this->getPhrases()[$i]);
                $review->setPublishedAt(new \DateTime('now + '.($i).'seconds'));
                $review->setRetailers('BLAH');
                $review->setPrice(1.99);
                $review->setStars(4);
                $review->setImage('d7f55ebaa8a25f973a3cecd1b61947c5.jpeg');
                $review->setRecipe($recipe);
                $manager->persist($review);
            }
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [ $username, $password, $roles];
            ['derek', 'pass', ['ROLE_SUPER_ADMIN']],
            ['john_user', 'pass', ['ROLE_USER']],
            ['admin', 'pass', ['ROLE_ADMIN']],
        ];
    }

    private function getRecipeData()
    {
        return [
            ['test1','test','fsdfsafs','d7f55ebaa8a25f973a3cecd1b61947c5.jpeg',$this->getReference('derek', 'john_user'),'gsdfg','10-15',true],
            ['test2','test','fsdfsafs','d7f55ebaa8a25f973a3cecd1b61947c5.jpeg',$this->getReference('derek', 'john_user'),'gsdfg','20-25',false],
            ['test3','test','fsdfsafs','d7f55ebaa8a25f973a3cecd1b61947c5.jpeg',$this->getReference('derek', 'john_user'),'gsdfg','10-15',true],
            ['test4','test','fsdfsafs','d7f55ebaa8a25f973a3cecd1b61947c5.jpeg',$this->getReference('derek', 'john_user'),'gsdfg','40-45',false],

        ];
    }
    /*
    private function getRecipeData()
    {
        $recipes = [];
        foreach ($this->getPhrases() as $i => $title) {
            // $postData = [$title, $slug, $summary, $content, $publishedAt, $author, $tags, $comments];
            $recipes[] = [
                $title,

                $this->getRandomText(),
                $this->getPostContent(),
                new \DateTime('now - '.$i.'days'),
                // Ensure that the first post is written by Jane Doe to simplify tests
                $this->getReference(['derek', 'john_user'][0 === $i ? 0 : random_int(0, 1)]),
               // $this->getRandomTags(),
            ];
        }

        return $recipes;
    }*/

    private function getPhrases(): array
    {
        return [
            'Distilled from molassis in copper pot stills and then aged in small oak casks for on average 12 years before being bottled',
            'Produced in Venezuela, which has a rich rum history dating to 1896, the distillery is located on the northern slopes of the Andes mountains',
            'Created in 1976 in the highlands of Guatemala, where it is distilled from fresh cane juice before aging at 7544 feet at 62 °F (17 °C)',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getRandomText(int $maxLength = 50): string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        while (mb_strlen($text = implode('. ', $phrases).'.') > $maxLength) {
            array_pop($phrases);
        }

        return $text;
    }

    private function getPostContent(): string
    {
        return <<<'MARKDOWN'
Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
deserunt mollit anim id est laborum.

  * Ut enim ad minim veniam
  * Quis nostrud exercitation *ullamco laboris*
  * Nisi ut aliquip ex ea commodo consequat

Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
luctus dolor.

Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
nulla vitae est.

Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
Sed in egestas erat.

Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
tincidunt, faucibus nisl in, aliquet libero.
MARKDOWN;
    }
    private function encodePassword($user, $plainPassword):string
    {
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        return $encodedPassword;
    }
}