<?php

namespace App\Entity;

use App\Entity\Image;
use App\Entity\Movie;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity(fields={"name"}, message="Ce nom de catégorie est déjà utilisé")
 */
class Category
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, inversedBy="categoryFeatured", cascade={"persist"})
     */
    private $featuredImage;

    /**
     * @ORM\OneToOne(targetEntity=Movie::class, inversedBy="category", cascade={"persist"})
     */
    private $featuredMovie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $explanatoryText;

    /**
     * @ORM\OneToMany(targetEntity=Monster::class, mappedBy="category")
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
            $monster->setCategory($this);
        }

        return $this;
    }

    public function removeMonster(Monster $monster): self
    {
        if ($this->monsters->removeElement($monster)) {
            // set the owning side to null (unless already changed)
            if ($monster->getCategory() === $this) {
                $monster->setCategory(null);
            }
        }

        return $this;
    }
}
