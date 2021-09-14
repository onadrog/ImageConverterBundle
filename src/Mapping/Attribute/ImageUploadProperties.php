<?php

namespace Onadrog\ImageConverterBundle\Mapping\Attribute;

use Attribute;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Onadrog\ImageConverterBundle\Service\ImageUtils;
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
        private ?string $mimeTypes = null,
        private PropertyAccessorInterface $propertyAccessorInterface,
        private array $config,
        private ImageUtils $imageUtils
    ) {
    }

    /**
     * Get the value of name.
     */
    public function getName(): ?string
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
        $prop = $this->retrieveFile($obj);
        foreach ($prop as $item) {
            $path = $this->imageUtils->strAppendSlash($this->config['media_uploads_path']);
            if (file_exists($path.$item)) {
                unlink($path.$item);
            }
        }
    }

    private function retrieveFile(object $object): array
    {
        $ref = new ReflectionClass($object);
        $prop = $ref->getProperties();
        $val = null;
        $array = [];
        foreach ($prop as $p) {
            if (!empty($p->getAttributes(self::class))) {
                $val = $p->getAttributes(self::class);
            }
        }
        $mimes = $this->propertyAccessorInterface->getValue($object, $val[0]->getArguments()['mimeTypes']);
        $name = $this->propertyAccessorInterface->getValue($object, $val[0]->getArguments()['name']);
        foreach ($mimes as $item) {
            $file = str_replace('webp', $item, $name);
            array_push($array, $file);
        }

        return $array;
    }
}
