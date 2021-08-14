<?php

namespace Onadrog\ImageConverterBundle\Entity;

use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;

#[ImageUpload]
class DummyWithAttribute
{
    private int $id;

    private string $name;

    private ?string $image;

    private string $slug;

    private array $dimension;

    /**
     * Get the value of dimension.
     */
    public function getDimension(): array
    {
        return $this->dimension;
    }

    /**
     * Set the value of dimension.
     *
     * @return self
     */
    public function setDimension(array $dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get the value of slug.
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug.
     *
     * @return self
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of image.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the value of image.
     *
     * @return self
     */
    public function setImage(?string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @return self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }
}
