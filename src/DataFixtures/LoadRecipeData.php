<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadRecipeData extends Fixture
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
        $user = $this->createUser('Jane', 'Doe');

        $r1 = new Recipe();
        $r1->setTitle("Bacardi");
        $r1->setSummary("Test Summary");
        $r1->setDescription("White Rum");
        $r1->setImage("images/test.png");
        $r1->setIngredients("Alcohol");
        $r1->setPrice(24.99);
        $r1->setPublic(true);
        $r1->setAuthor($user);

        $r2 = new Recipe();
        $r2->setTitle("Bacardi 2");
        $r2->setSummary("Test Summary");
        $r2->setDescription("White Rum");
        $r2->setImage("images/test.png");
        $r2->setIngredients("Alcohol");
        $r2->setPrice(24.99);
        $r2->setPublic(true);
        $r1->setAuthor($user);

        $r3 = new Recipe();
        $r3->setTitle("Bacardi");
        $r3->setSummary("Test Summary");
        $r3->setDescription("White Rum");
        $r3->setImage("images/test.png");
        $r3->setIngredients("Alcohol");
        $r3->setPrice(24.99);
        $r3->setPublic(true);
        $r1->setAuthor($user);

        $manager->persist($user);
        $manager->persist($r1);
        $manager->persist($r2);
        $manager->persist($r3);

        $manager->flush();
    }

    /**
     * @param $username
     * @param $plainPassword
     * @param array $roles // default to ROLE_USER if no ROLE supplied
     *
     * @return User
     */
    private function createUser($username, $plainPassword, $roles = ['ROLE_USER']):User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setRoles($roles);

        // password - and encoding
        $encodedPassword = $this->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        return $user;
    }

    private function encodePassword($user, $plainPassword):string
    {
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        return $encodedPassword;
    }
}