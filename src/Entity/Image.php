<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Monster;
use App\Entity\User;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @UniqueEntity(fields={"name"}, message="Ce nom est déjà utilisé par une autre image")
 * @Vich\Uploadable
 */
class Image
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
     * @Vich\UploadableField(mapping="bestiaire_images", fileNameProperty="file_name")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="images")
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
     * @ORM\OneToOne(targetEntity=Category::class, mappedBy="featuredImage", cascade={"persist"})
     */
    private $categoryFeatured;

    /**
     * @ORM\OneToOne(targetEntity=Monster::class, mappedBy="featuredImage", cascade={"persist"})
     */
    private $monsterFeatured;

    /**
     * @ORM\ManyToMany(targetEntity=Monster::class, mappedBy="images", cascade={"persist"})
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Tu dois choisir que monstre est / sont sur la photo",
     */
    private $monsters;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="image")
     */
    private $comments;

    public function __construct()
    {
        $this->monsters = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getImageFile(): ?File
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

    public function getCategoryFeatured(): ?Category
    {
        return $this->categoryFeatured;
    }

    public function setCategoryFeatured(?Category $categoryFeatured): self
    {
        // unset the owning side of the relation if necessary
        if ($categoryFeatured === null && $this->categoryFeatured !== null) {
            $this->categoryFeatured->setFeaturedImage(null);
        }

        // set the owning side of the relation if necessary
        if ($categoryFeatured !== null && $categoryFeatured->getFeaturedImage() !== $this) {
            $categoryFeatured->setFeaturedImage($this);
        }

        $this->categoryFeatured = $categoryFeatured;

        return $this;
    }

    public function getMonsterFeatured(): ?Monster
    {
        return $this->monsterFeatured;
    }

    public function setMonsterFeatured(?Monster $monsterFeatured): self
    {
        // unset the owning side of the relation if necessary
        if ($monsterFeatured === null && $this->monsterFeatured !== null) {
            $this->monsterFeatured->setFeaturedImage(null);
        }

        // set the owning side of the relation if necessary
        if ($monsterFeatured !== null && $monsterFeatured->getFeaturedImage() !== $this) {
            $monsterFeatured->setFeaturedImage($this);
        }

        $this->monsterFeatured = $monsterFeatured;

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
            $monster->addImage($this);
        }

        return $this;
    }

    public function removeMonster(Monster $monster): self
    {
        if ($this->monsters->removeElement($monster)) {
            $monster->removeImage($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setImage($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getImage() === $this) {
                $comment->setImage(null);
            }
        }

        return $this;
    }
}
