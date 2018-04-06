<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Recipe;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review
{
    const NUM_ITEMS = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string")
     */
    private $summary;

    /**
     * @ORM\Column(type="string")
     */
    private $retailers;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublicReview;

    /**
     * @ORM\Column(type="boolean")
     */
    private $requestReviewPublic;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="User", inversedBy="voter")
     */
    private $votes;

    public function getImage2()
    {
        return $this->image2;
    }

    public function setImage2($image2): void
    {
        $this->image2 = $image2;
    }

    /**
     * @ORM\Column(type="float")
     */
    private $stars;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Image
     */
    private $image;

    /**
     * @var string $image
     * @Assert\File( maxSize = "1024k", mimeTypesMessage = "Please upload a valid Image")
     */
    private $image2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=true)
     */
    private $recipe;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    /**
     * @param mixed $publishedAt
     */
    public function setPublishedAt(\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return mixed
     */
    public function getSummary(): ?string
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
    public function getRetailers(): ?string
    {
        return $this->retailers;
    }

    /**
     * @param mixed $retailers
     */
    public function setRetailers($retailers): void
    {
        $this->retailers = $retailers;
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
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * @param mixed $stars
     */
    public function setStars($stars): void
    {
        $this->stars = $stars;
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

    public function getRecipe()
    {
        return $this->recipe;
    }
    public function setRecipe($recipe = null)
    {
        $this->recipe = $recipe;
    }

    public function __toString()
    {
        return $this->id . ': ' . $this->getSummary();
    }

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->requestReviewPublic = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getisPublicReview()
    {
        return $this->isPublicReview;
    }

    /**
     * @param mixed $isPublicReview
     */
    public function setIsPublicReview($isPublicReview): void
    {
        $this->isPublicReview = $isPublicReview;
    }

    /**
     * @return mixed
     */
    public function getRequestReviewPublic()
    {
        return $this->requestReviewPublic;
    }

    /**
     * @param mixed $requestReviewPublic
     */
    public function setRequestReviewPublic(User $requestReviewPublic): void
    {
        $this->requestReviewPublic = $requestReviewPublic;
    }

}
