<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUploadProperties;
use Onadrog\ImageConverterBundle\Mock\Entity\Repository\SoloFileRepository;

/**
 * @ORM\Entity(repositoryClass=SoloFileRepository::class)
 */
#[ImageUpload]
class SoloFile
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
     * @ORM\Column(type="json")
     */
    private $dimension = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    #[ImageUploadProperties(name: 'name', slug: 'slug', dimension: 'dimension', alt: 'alt', mimeTypes: 'mimeTypes')]
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    /**
     * @ORM\Column(type="json")
     */
    private $mimeTypes = [];

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

    public function getDimension(): ?array
    {
        return $this->dimension;
    }

    public function setDimension(array $dimension): self
    {
        $this->dimension = $dimension;

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

    public function getMimeTypes(): ?array
    {
        return $this->mimeTypes;
    }

    public function setMimeTypes(array $mimeTypes): self
    {
        $this->mimeTypes = $mimeTypes;

        return $this;
    }
}
