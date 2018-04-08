<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;

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
     * @Assert\NotBlank()
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];
    /**
     * @ORM\Column(type="string", length=25, unique=false)
     */
    private $firstname;
    /**
     * @ORM\Column(type="string", length=25, unique=false)
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
     *
     */
  //  private $makeRecipesPublic;

    /**
     * @var Review[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Review",
     *      mappedBy="requestReviewPublic",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     */
  //  private $makeReviewsPublic;

    /**
     *
     *
     * @ORM\OneToMany(targetEntity="Review", mappedBy="votes")
     */
    private $voter;

    /**
     * @var Review[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Review",
     *      mappedBy="author",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     */
    private $reviewAuthor;

    /**
     * @var Recipe[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Recipe",
     *      mappedBy="author",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     */
    private $recipeAuthor;

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
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @return mixed
     */
    public function getVoter()
    {
        return $this->voter;
    }

    /**
     * @param mixed $voter
     */
    public function setVoter($voter): void
    {
        $this->voter = $voter;
    }

}