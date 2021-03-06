<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;
use Onadrog\ImageConverterBundle\Mock\Entity\Repository\DummyWithAttributeRepository;

/**
 * @ORM\Entity(repositoryClass=DummyWithAttributeRepository::class)
 */
#[ImageUpload]
class DummyWithAttribute
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
}
