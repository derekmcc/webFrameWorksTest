<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadRecipeData extends Fixture implements DependentFixtureInterface
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
       // $this->loadUsers($manager);

        $r1 = new Recipe();
        $r1->setTitle("Bacardi");
        $r1->setSummary("Test Summary");
        $r1->setDescription("White Rum");
        $r1->setImage("images/test.png");
        $r1->setIngredients("Alcohol");
        $r1->setPrice(24.99);
        $r1->setPublic(true);
        $r1->setAuthor($this->getReference($this->getReference(LoadUsers::USER_REFERENCE)));

        $r2 = new Recipe();
        $r2->setTitle("Bacardi 2");
        $r2->setSummary("Test Summary");
        $r2->setDescription("White Rum");
        $r2->setImage("images/test.png");
        $r2->setIngredients("Alcohol");
        $r2->setPrice(24.99);
        $r2->setPublic(true);
        $r2->setAuthor($this->getReference($this->getReference(LoadUsers::USER_REFERENCE)));

        $r3 = new Recipe();
        $r3->setTitle("Bacardi");
        $r3->setSummary("Test Summary");
        $r3->setDescription("White Rum");
        $r3->setImage("images/test.png");
        $r3->setIngredients("Alcohol");
        $r3->setPrice(24.99);
        $r3->setPublic(true);
        $r3->setAuthor($this->getReference($this->getReference(LoadUsers::USER_REFERENCE)));

       // $manager->persist($user);
        $manager->persist($r1);
        $manager->persist($r2);
        $manager->persist($r3);

        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            LoadUsers::class,
        );
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [ $username, $password, $roles]) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->encodePassword($user, $password));
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($user->getId(), $user);
           // $this->setReference($user->getId(), $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [ $username, $password, $roles];
            ['jane_admin', 'pass', ['ROLE_ADMIN']],
            ['tom_admin', 'pass', ['ROLE_ADMIN']],
            ['john_user', 'pass', ['ROLE_USER']],
        ];
    }

    private function encodePassword($user, $plainPassword):string
    {
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        return $encodedPassword;
    }
}