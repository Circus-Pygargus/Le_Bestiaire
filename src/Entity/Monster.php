<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Movie;
use App\Repository\MonsterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MonsterRepository::class)
 * @UniqueEntity(fields={"name"}, message="Ce nom est déjà utilisé par un autre monstre")
 */
class Monster
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $nicknames;

    /**
     * @ORM\ManyToMany(targetEntity=Monster::class, inversedBy="Children")
     */
    private $parents;

    /**
     * @ORM\ManyToMany(targetEntity=Monster::class, mappedBy="parents")
     */
    private $Children;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $arrivalDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $leavingDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reasonForLeaving;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, inversedBy="monsterFeatured", cascade={"persist"})
     */
    private $featuredImage;

    /**
     * @ORM\OneToOne(targetEntity=Movie::class, inversedBy="featuredMonster", cascade={"persist"})
     */
    private $featuredMovie;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class, inversedBy="monsters")
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="monsters")
     */
    private $movies;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="monsters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cossard;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $explanatoryText;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="monster")
     */
    private $Comments;

    public function __construct()
    {
        $this->parents = new ArrayCollection();
        $this->Children = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->movies = new ArrayCollection();
        $this->Comments = new ArrayCollection();
    }

    public function __toString (): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getNicknames(): ?string
    {
        return $this->nicknames;
    }

    public function setNicknames(?string $nicknames): self
    {
        $this->nicknames = $nicknames;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(self $parent): self
    {
        if (!$this->parents->contains($parent)) {
            $this->parents[] = $parent;
        }

        return $this;
    }

    public function removeParent(self $parent): self
    {
        $this->parents->removeElement($parent);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->Children;
    }

    public function addChild(self $child): self
    {
        if (!$this->Children->contains($child)) {
            $this->Children[] = $child;
            $child->addParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->Children->removeElement($child)) {
            $child->removeParent($this);
        }

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(?\DateTimeInterface $arrivalDate): self
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getLeavingDate(): ?\DateTimeInterface
    {
        return $this->leavingDate;
    }

    public function setLeavingDate(?\DateTimeInterface $leavingDate): self
    {
        $this->leavingDate = $leavingDate;

        return $this;
    }

    public function getReasonForLeaving(): ?string
    {
        return $this->reasonForLeaving;
    }

    public function setReasonForLeaving(?string $reasonForLeaving): self
    {
        $this->reasonForLeaving = $reasonForLeaving;

        return $this;
    }

    public function getFeaturedImage(): ?Image
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(?Image $featuredImage): self
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    public function getFeaturedMovie(): ?Movie
    {
        return $this->featuredMovie;
    }

    public function setFeaturedMovie(?Movie $featuredMovie): self
    {
        $this->featuredMovie = $featuredMovie;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        $this->movies->removeElement($movie);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCossard(): ?bool
    {
        return $this->cossard;
    }

    public function setCossard(bool $cossard): self
    {
        $this->cossard = $cossard;

        return $this;
    }

    public function getExplanatoryText(): ?string
    {
        return $this->explanatoryText;
    }

    public function setExplanatoryText(?string $explanatoryText): self
    {
        $this->explanatoryText = $explanatoryText;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->Comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->Comments->contains($comment)) {
            $this->Comments[] = $comment;
            $comment->setMonster($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->Comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMonster() === $this) {
                $comment->setMonster(null);
            }
        }

        return $this;
    }
}
