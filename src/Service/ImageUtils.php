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
final class ImageUtils
{
    public const CACHE_KEY = 'image_converter_cache_key';
    public const ENTITY_CACHE_KEY = 'imc_entity_cache_key';

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
    public function namer(string $originalName, array $option): array
    {
        $slug = new AsciiSlugger();
        $safeName = (string) $slug->slug($originalName);

        $slug = match ($option['namer']) {
            'default' => (string) $safeName,
            'uuid' => (string) Uuid::v6(),
            'mixed' => str_replace('.', '-', uniqid($safeName.'-', true)),
        };

        $path = $this->strAppendSlash($option['public_path']);

        $finalSlug = $path.$slug;

        return ['slug' => $finalSlug, 'safename' => $slug];
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
     */
    public static function guessMappedClass(object $class, string $property): array
    {
        $array = [];
        $Refclass = new ReflectionClass($class);
        $attribute = $Refclass->getAttributes(ImageUpload::class);
        // ImageUpload Attribute not found on Entity
        if (empty($attribute)) {
            $prop = $Refclass->getProperty($property);
            $annotation = self::readProperty($prop);
            foreach ($annotation as $anno) {
                if (isset($anno->targetEntity)) {
                    $v = $anno->targetEntity;
                    $prop = $anno->mappedBy ?? $anno->inversedBy;
                    $array = self::readRelationalMapping($v, $prop, $property);
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
    private static function readRelationalMapping(string $class, string $property, string $form_property): array
    {
        $target = new ReflectionClass($class);
        if (!$target->getAttributes(ImageUpload::class)) {
            throw new RuntimeException(sprintf('You probably forgot to use ImageUpload attribute in class %s', $target->name));
        }
        $target->newInstance();
        $file = $target->getProperties();
        $arguments = [];
        foreach ($file as $f) {
            if (!empty($f->getAttributes(ImageUploadProperties::class))) {
                $arguments = $f->getAttributes(ImageUploadProperties::class);
            }
        }
        $argsArray = [
            'form_property' => $form_property,
            'property' => $property,
            'entity' => $target->name,
            'relation' => true,
        ];
        foreach ($arguments as $arg) {
            $argsArray = array_merge($argsArray, $arg->getArguments());
        }

        return $argsArray;
    }

    /**
     * Read class with ImageUpload Attribute.
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
        $argsArray = [
            'property' => $property,
            'entity' => $entity->name,
            'relation' => false,
        ];
        foreach ($arguments as $arg) {
            $argsArray = array_merge($argsArray, $arg->getArguments());
        }

        return $argsArray;
    }

    /**
     * Check if the given config have '/' at start and end,
     * append if not.
     */
    public function strAppendSlash(string $string): string
    {
        if (!str_ends_with($string, '/')) {
            $string .= '/';
        }
        if (!str_starts_with($string, '/')) {
            $string = '/'.$string;
        }

        return $string;
    }
}
