<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Monster;
use App\Entity\User;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
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
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="bestiaire_movies", fileNameProperty="file_name")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedBy;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=Category::class, mappedBy="featuredMovie", cascade={"persist"})
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity=Monster::class, mappedBy="featuredMovie", cascade={"persist"})
     */
    private $featuredMonster;

    /**
     * @ORM\ManyToMany(targetEntity=Monster::class, mappedBy="movies", cascade={"persist"})
     */
    private $monsters;

    public function __construct()
    {
        $this->monsters = new ArrayCollection();
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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->postedBy;
    }

    public function setPostedBy(?User $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        // unset the owning side of the relation if necessary
        if ($category === null && $this->category !== null) {
            $this->category->setFeaturedMovie(null);
        }

        // set the owning side of the relation if necessary
        if ($category !== null && $category->getFeaturedMovie() !== $this) {
            $category->setFeaturedMovie($this);
        }

        $this->category = $category;

        return $this;
    }

    public function getFeaturedMonster(): ?Monster
    {
        return $this->featuredMonster;
    }

    public function setFeaturedMonster(?Monster $featuredMonster): self
    {
        // unset the owning side of the relation if necessary
        if ($featuredMonster === null && $this->featuredMonster !== null) {
            $this->featuredMonster->setFeaturedMovie(null);
        }

        // set the owning side of the relation if necessary
        if ($featuredMonster !== null && $featuredMonster->getFeaturedMovie() !== $this) {
            $featuredMonster->setFeaturedMovie($this);
        }

        $this->featuredMonster = $featuredMonster;

        return $this;
    }

    /**
     * @return Collection|Monster[]
     */
    public function getMonsters(): Collection
    {
        return $this->monsters;
    }

    public function addMonster(Monster $monster): self
    {
        if (!$this->monsters->contains($monster)) {
            $this->monsters[] = $monster;
            $monster->addMovie($this);
        }

        return $this;
    }

    public function removeMonster(Monster $monster): self
    {
        if ($this->monsters->removeElement($monster)) {
            $monster->removeMovie($this);
        }

        return $this;
    }
}
