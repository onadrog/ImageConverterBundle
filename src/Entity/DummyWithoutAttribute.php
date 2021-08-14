<?php

namespace Onadrog\ImageConverterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class DummyWithoutAttribute
{
    #[Orm\OneToMany(targetEntity: Product::class, mappedBy: 'yay')]
    private ?string $yo;
}
