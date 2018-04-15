<?php
/**
 * User entity for creating users.
 */
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Start of the user entity class.
 *
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * Class User
 * @package App\Entity
 */
class User implements UserInterface, \Serializable
{
    /**
     * User id.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Users username.
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * Users password for storing in database.
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * Plain password for comparing passwords match.
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * Users email.
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * Users roles.
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * Users first name.
     * @ORM\Column(type="string", length=25, unique=false)
     */
    private $firstname;

    /**
     * Users surname.
     * @ORM\Column(type="string", length=25, unique=false)
     */
    private $surname;


    /**
     * Users voter which stores if a user has voted on a review.
     * @ORM\OneToMany(targetEntity="Review", mappedBy="votes")
     */
    private $voter;

    /**
     * Foreign key reference to the reviews author.
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
     * Foreign key reference to the recipes author.
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

    /**
     * Gets the salt that has being added to passwords.
     * @return null|string
     */
    public function getSalt()
    {
        // no salt needed since we are using bcrypt
        return null;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
    }
    /**
     * Serializes data in the appropriate format.
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
     * Deserializes data into the given type.
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * Gets the roles the user holds.
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Gets the id of the user.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the roles the user has.
     * @param $roles
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Gets the users username.
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the users username.
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * Gets the users password.
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the users password.
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Gets the first name of the user.
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets the first name of the user.
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * Gets the surname of the user.
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Sets the surname of the user.
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Gets the email address of the user.
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email address of the user.
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Gets the plain password of the user.
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Assigns the value of password to the value of plain password.
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Added as the data fixtures wouldn't work without it for
     * for some reason.
     */
    public function getImage(){
    }
}