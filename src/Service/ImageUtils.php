<?php

namespace Onadrog\ImageConverterBundle\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use GdImage;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUploadProperties;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 */
class ImageUtils
{
    /**
     * Create a GdImage to be converted by imagewebp func.
     */
    public function createGdImg(string $extension, string $imagePath): ?GdImage
    {
        if ('jpg' === $extension) {
            return imagecreatefromjpeg($imagePath);
        } else {
            $img = 'imagecreatefrom'.$extension;

            return $img($imagePath);
        }
    }

    /**
     * Slugify / return choosen 'namer' option.
     */
    public function namer(string $originalName, string $option): string
    {
        $slug = new AsciiSlugger();
        $safeName = $slug->slug($originalName);

        return match ($option) {
            'default' => $safeName,
            'uuid' => (string) Uuid::v6(),
            'mixed' => str_replace('.', '-', uniqid($safeName.'-', true)),
        };
    }

    /**
     * Return the mapped class with the ImageUpload Attribute.
     */
    public static function guessMappedClass(object $class, string $property): array
    {
        $array = [];
        $Refclass = new ReflectionClass($class);
        $attribute = $Refclass->getAttributes(ImageUpload::class);
        // ImageUpload Attribute not found on Entity
        if (empty($attribute)) {
            $prop = $Refclass->getProperty($property);
            $reader = new AnnotationReader();
            //$reader = new AttributeReader(); <-- Waiting for PR to be merged https://github.com/symfony/maker-bundle/pull/920
            $annotation = $reader->getPropertyAnnotations($prop);
            foreach ($annotation as $anno) {
                if (isset($anno->targetEntity)) {
                    $v = $anno->targetEntity;
                    $prop = $anno->mappedBy ?? $anno->inversedBy;
                    $array = self::readRelationalMapping($v, $prop);
                }
            }
        } else {
            $array = self::readClassAttribute($Refclass, $property);
        }

        return $array;
    }

    /**
     * Retrieve metadata from relation mapping attributes.
     */
    private static function readRelationalMapping(string $class, string $property): array
    {
        $target = new ReflectionClass($class);
        if (!$target->getAttributes(ImageUpload::class)) {
            throw new RuntimeException(sprintf('You probably forgot to use ImageUpload attribute in class %s', $target->name));
        }
        $target->newInstance();
        $file = $target->getProperty($property);
        $arguments = $file->getAttributes(ImageUploadProperties::class);
        $argsArray = [
            'property' => $property,
            'entity' => $target,
        ];
        foreach ($arguments as $arg) {
            $entityTargetArray = array_merge($argsArray, $arg->getArguments());
        }

        return $entityTargetArray ?? []; //TODO persist entity
    }

    /**
     * Read class ImageUpload Attribute.
     */
    public static function readClassAttribute(ReflectionClass $reflectionClass, string $property): array
    {
        $reflectionClass->newInstance();
        $file = $reflectionClass->getProperty($property);
        $arguments = $file->getAttributes(ImageUploadProperties::class);
        if (!$arguments) {
            throw new RuntimeException(sprintf('You probably forgot to use ImageUploadProperties attribute in class %s', $reflectionClass->name));
        }
        $argsArray = [
            'property' => $property,
            'entity' => $reflectionClass,
        ];
        foreach ($arguments as $arg) {
            $entityTargetArray = array_merge($argsArray, $arg->getArguments());
        }

        return $entityTargetArray ?? []; //TODO persist entity
    }
}
