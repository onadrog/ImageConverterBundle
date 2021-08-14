<?php

namespace Onadrog\ImageConverterBundle\Mapping\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ImageUploadProperties
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $slug = null,
        protected ?string $dimension = null
    ) {
    }

    /**
     * Get the value of name.
     */
    public function getImage(): ?string
    {
        return $this->name;
    }

    /**
     * Get the value of slug.
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Get the value of dimension.
     */
    public function getDimension(): ?string
    {
        return $this->dimension;
    }
}
