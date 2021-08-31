<?php

namespace Onadrog\ImageConverterBundle\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use GdImage;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUploadProperties;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 *
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
    public function namer(string $originalName, string $option): array
    {
        $slug = new AsciiSlugger();
        $safeName = (string) $slug->slug($originalName);

        $slug = match ($option) {
            'default' => (string) $safeName,
            'uuid' => (string) Uuid::v6(),
            'mixed' => str_replace('.', '-', uniqid($safeName.'-', true)),
        };

        return ['slug' => $slug, 'safename' => $safeName];
    }

    /**
     * @return array<object>
     */
    private static function readProperty(ReflectionProperty $property)
    {
        $reader = new AnnotationReader();
        //$reader = new AttributeReader(); <-- Waiting for PR to be merged https://github.com/symfony/maker-bundle/pull/920
        return $reader->getPropertyAnnotations($property);
    }

    /**
     * Return the mapped class with the ImageUpload Attribute.
     * TODO: cache array result.
     */
    public static function guessMappedClass(object $class, string $property): array
    {
        $array = [];
        $Refclass = new ReflectionClass($class);
        $attribute = $Refclass->getAttributes(ImageUpload::class);
        // ImageUpload Attribute not found on Entity
        if (empty($attribute)) {
            $prop = $Refclass->getProperty($property);
            /*  $reader = new AnnotationReader();
            //$reader = new AttributeReader(); <-- Waiting for PR to be merged https://github.com/symfony/maker-bundle/pull/920
            $annotation = $reader->getPropertyAnnotations($prop); */
            $annotation = self::readProperty($prop);
            foreach ($annotation as $anno) {
                if (isset($anno->targetEntity)) {
                    $v = $anno->targetEntity;
                    $prop = $anno->mappedBy ?? $anno->inversedBy;
                    $array = self::readRelationalMapping($v, $prop);
                }
            }
        } else {
            $array = self::readClassAttribute($class, $property);
        }

        return $array;
    }

    /**
     * Retrieve metadata attributes from relation mapping.
     */
    private static function readRelationalMapping(string $class, string $property): array
    {
        $target = new ReflectionClass($class);
        if (!$target->getAttributes(ImageUpload::class)) {
            throw new RuntimeException(sprintf('You probably forgot to use ImageUpload attribute in class %s', $target->name));
        }
        $target->newInstance();
        $file = $target->getProperties();
        $arguments = [];
        foreach ($file as $f) {
            $arguments = $f->getAttributes(ImageUploadProperties::class);
        }
        $argsArray = [
            'property' => $property,
            'entity' => $target,
            'relation' => true,
        ];
        foreach ($arguments as $arg) {
            $argsArray = array_merge($argsArray, $arg->getArguments());
        }

        return $argsArray;
    }

    /**
     * Read class ImageUpload Attribute.
     */
    public static function readClassAttribute(object $class, string $property): array
    {
        $target = new ReflectionClass($class);
        $props = self::readProperty($target->getProperty($property));
        $entity = $target;
        foreach ($props as $prop) {
            if (isset($prop->targetEntity)) {
                $entity = new ReflectionClass($prop->targetEntity);
            }
        }
        $target->newInstance();
        $file = $target->getProperty($property);
        $arguments = $file->getAttributes(ImageUploadProperties::class);
        if (!$arguments) {
            throw new RuntimeException(sprintf('You probably forgot to use ImageUploadProperties attribute in class %s', $target->name));
        }
        // TODO: Remove this array if it's not a relational mapping
        $argsArray = [
            'property' => $property,
            'entity' => $entity,
            'relation' => false,
        ];
        foreach ($arguments as $arg) {
            $argsArray = array_merge($argsArray, $arg->getArguments());
        }

        return $argsArray;
    }
}
