<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Onadrog\ImageConverterBundle\Mock\Entity\Repository\ProductRepository;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="products")
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity=DummyWithoutAttribute::class, mappedBy="product")
     */
    private $dummy;

    public function __construct()
    {
        $this->dummy = new ArrayCollection();
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

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection|DummyWithoutAttribute[]
     */
    public function getDummy(): Collection
    {
        return $this->dummy;
    }

    public function addDummy(DummyWithoutAttribute $dummy): self
    {
        if (!$this->dummy->contains($dummy)) {
            $this->dummy[] = $dummy;
            $dummy->setProduct($this);
        }

        return $this;
    }

    public function removeDummy(DummyWithoutAttribute $dummy): self
    {
        if ($this->dummy->removeElement($dummy)) {
            // set the owning side to null (unless already changed)
            if ($dummy->getProduct() === $this) {
                $dummy->setProduct(null);
            }
        }

        return $this;
    }
}
