<?php

namespace Onadrog\ImageConverterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Product
{
    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'file')]
    protected ?string $image;

    #[ORM\ManyToOne(targetEntity: DummyWithoutAttribute::class, inversedBy: 'yo')]
    protected ?string $yay;
}
