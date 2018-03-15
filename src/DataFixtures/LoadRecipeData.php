<?php

namespace App\DataFixtures;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRecipeData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $r1 = new Recipe();
        $r1->setTitle("Bacardi");
        $r1->setSummary("Test Summary");
        $r1->setDescription("White Rum");
        $r1->setImage("images/test.png");
        $r1->setIngredients("Alcohol");
        $r1->setPrice(24.99);

        $r2 = new Recipe();
        $r2->setTitle("Bacardi 2");
        $r2->setSummary("Test Summary");
        $r2->setDescription("White Rum");
        $r2->setImage("images/test.png");
        $r2->setIngredients("Alcohol");
        $r2->setPrice(24.99);

        $r3 = new Recipe();
        $r3->setTitle("Bacardi");
        $r3->setSummary("Test Summary");
        $r3->setDescription("White Rum");
        $r3->setImage("images/test.png");
        $r3->setIngredients("Alcohol");
        $r3->setPrice(24.99);

        $manager->persist($r1);
        $manager->persist($r2);
        $manager->persist($r3);

        $manager->flush();
    }
}