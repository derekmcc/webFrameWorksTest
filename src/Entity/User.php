<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */

    private $password;
    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $surname;

    /**
     * @var Recipe[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Recipe",
     *      mappedBy="requestRecipePublic",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     */
    private $makeRecipesPublic;

    /**
     * @var Review[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Review",
     *      mappedBy="requestReviewPublic",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     * @ORM\OrderBy({"publishedAt": "DESC"})
     */
    private $makeReviewsPublic;

    public function getSalt()
    {
        // no salt needed since we are using bcrypt
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return Recipe[]|ArrayCollection
     */
    public function getMakeRecipesPublic()
    {
        return $this->makeRecipesPublic;
    }

    /**
     * @param Recipe[]|ArrayCollection $makeRecipesPublic
     */
    public function setMakeRecipesPublic(Recipe $makePublic): void
    {
        $makePublic->setRequestRecipePublic($this);
        if (!$this->makeRecipesPublic->contains($makePublic)) {
            $this->makeRecipesPublic->add($makePublic);
        }
    }

    /**
     * @return Review[]|ArrayCollection
     */
    public function getMankeReviewsPublic()
    {
        return $this->mankeReviewsPublic;
    }

    /**
     * @param Review[]|ArrayCollection $mankeReviewsPublic
     */
    public function setMankeReviewsPublic($mankeReviewsPublic): void
    {
        $this->mankeReviewsPublic = $mankeReviewsPublic;
    }

}
