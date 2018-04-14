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
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reviewAuthor")
     * @ORM\JoinColumn(nullable=true)
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="voter")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $votes;

    /**
     * @ORM\Column(type="float")
     */
    private $stars;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\NotBlank(message="Please, upload the image as a jpg")
     * @Assert\File(maxSize="10M",
     *          mimeTypes={
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif"
     *          }
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $upVotes = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $downVotes = 0;

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
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor(User $author)
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

//    public function __toString()
//    {
//        return $this->id . ': ' . $this->getSummary();
//    }

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
    public function setRequestReviewPublic($requestReviewPublic): void
    {
        $this->requestReviewPublic = $requestReviewPublic;
    }

    /**
     * @return User|null
     */
    public function getVotes(): ?User
    {
        return $this->votes;
    }

    /**
     * @param User $votes
     */
    public function setVotes(User $votes): void
    {
        $this->votes = $votes;
    }

    /**
     * @return mixed
     */
    public function getUpVotes()
    {
        return $this->upVotes;
    }

    /**
     * @param mixed $upVotes
     */
    public function setUpVotes($upVotes): void
    {
        $this->upVotes = $upVotes;
    }

    /**
     * @return mixed
     */
    public function getDownVotes()
    {
        return $this->downVotes;
    }

    /**
     * @param mixed $downVotes
     */
    public function setDownVotes($downVotes): void
    {
        $this->downVotes = $downVotes;
    }
}
