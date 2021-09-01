<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUploadProperties;
use Onadrog\ImageConverterBundle\Mock\Entity\Repository\MediaRepository;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
#[ImageUpload]
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="json")
     */
    private $dimension = [];

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="media")
     */
    private $products;

    #[ImageUploadProperties(name: 'name', slug: 'slug', dimension: 'dimension', alt: 'alt')]
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDimension(): ?array
    {
        return $this->dimension;
    }

    public function setDimension(array $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setMedia($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getMedia() === $this) {
                $product->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of file.
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file.
     *
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
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
}
