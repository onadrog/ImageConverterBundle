<?php

namespace Onadrog\ImageConverterBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORm\Mapping as ORM;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUploadProperties;

#[ImageUpload]
#[ORM\Entity()]
class Media extends Dummy
{
    #[ImageUploadProperties(name: 'name', slug: 'slug', dimension: 'dimansion')]
    #[ORM\OneToMany(mappedBy: 'image', targetEntity: 'Product')]
    protected $file;

    /**
     * Get the value of file.
     */
    public function getFile(): Collection
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
}
