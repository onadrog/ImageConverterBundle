<?php

namespace Onadrog\ImageConverterBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class EntityController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getEntity(string $entity): array
    {
        $em = $this->entityManager;

        return $em->getRepository($entity)->findAll();
    }
}
