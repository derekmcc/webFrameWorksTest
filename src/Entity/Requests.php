<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 04/04/2018
 * Time: 19:00
 */

namespace App\Entity;


class Requests
{
   // private $id;

   // private $request;

    //////////////////////-----USER--------------------
    /*
     * @return Recipe[]|ArrayCollection
     *
    public function getMakeRecipesPublic()
    {
        return $this->makeRecipesPublic;
    }
    public function setMakeRecipesPublic(Recipe $makeRecipesPublic): void
    {
        $makeRecipesPublic->setRequestRecipePublic($this);
        if (!$this->makeRecipesPublic->contains($makeRecipesPublic)) {
            $this->makeRecipesPublic->add($makeRecipesPublic);
        }
    }
    /**
     * @return Review[]|ArrayCollection
     *
    public function getMakeReviewsPublic()
    {
        return $this->makeReviewsPublic;
    }
    /**
     * @param Review[]|ArrayCollection $makeReviewsPublic
     *
    public function setMakeReviewsPublic($makeReviewsPublic): void
    {
        $this->makeReviewsPublic = $makeReviewsPublic;
    }

    public function __toString()
    {
        return '1';
    }

    public function __construct()
    {
        $this->makeRecipesPublic = new ArrayCollection();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('username') ->add('makeRecipesPublic') ;
    }

    //////////////------------------------------RECIP------------------
        /**
     * @return mixed
     *
    public function getRequestRecipePublic()
    {
        return $this->requestRecipePublic;
    }
    /**
     * @param mixed $requestRecipePublic
     *
    public function setRequestRecipePublic($requestRecipePublic = null): void
    {
        $this->requestRecipePublic = $requestRecipePublic;
    }

    public function __toString()
    {
        return $this->id . ': ' . $this->getRequestRecipePublic();
    }
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="makeRecipesPublic")
     * @ORM\JoinColumn(nullable=true)
     *

    private $requestRecipePublic;
}*/
}