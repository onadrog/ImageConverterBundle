<?php

namespace Onadrog\ImageConverterBundle\Mapping\Attribute;

use Attribute;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ImageUploadProperties implements EventSubscriberInterface
{
    public function __construct(
        private ?string $name = null,
        private ?string $slug = null,
        private ?string $dimension = null,
        private ?string $alt = null,
        private PropertyAccessorInterface $propertyAccessorInterface,
        private array $config
    ) {
    }

    /**
     * Get the value of name.
     */
    public function getName(object $object): ?string
    {
        $ref = new ReflectionClass($object);
        $prop = $ref->getProperties();
        $val = null;
        foreach ($prop as $p) {
            if (!empty($p->getAttributes(self::class))) {
                $val = $p->getAttributes(self::class);
            }
        }

        return $val[0]->getArguments()['name'];
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

    /**
     * Get the value of alt.
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
        ];
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $obj = $args->getObject();
        $prop = $this->getName($obj);
        $file = $this->propertyAccessorInterface->getValue($obj, $prop);
        if (file_exists($this->config['media_uploads_path'].$file)) {
            unlink($this->config['media_uploads_path'].$file);
        }
    }
}
