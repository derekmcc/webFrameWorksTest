<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @ORM\Column(type="string")
     */
    private $summary;
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the image as a jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $image;
    /**
     * @ORM\Column(type="string")
     */
    private $ingredients;
    /**
     * @ORM\Column(type="float")
     */
    private $price;
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;
    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     */
    public function setPublic($public): void
    {
        $this->public = $public;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }
    /**
     * @param mixed $summary
     */
    public function setSummary($summary): void
    {
        $this->summary = $summary;
    }
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }
    /**
     * @return mixed
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }
    /**
     * @param mixed $ingredients
     */
    public function setIngredients($ingredients): void
    {
        $this->ingredients = $ingredients;
    }
    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }
    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="recipes")
     */
    private $reviews;
    public function __construct()
    {
        $this->reviews = new ArrayCollection();

    }
    public function getReviews()
    {
        return $this->reviews;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title') ->add('reviews')
            ->add('image', FileType::class, array('data_class' => null));
          //  ->add('file', FileType::class, array('data_class' => null));
    }
}