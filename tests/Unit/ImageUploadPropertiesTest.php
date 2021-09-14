<?php

namespace Onadrog\ImageConverterBundle\Tests\Unit;

use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUploadProperties;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\Media;
use Onadrog\ImageConverterBundle\Service\ImageUtils;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageUploadPropertiesTest extends TestCase
{
    public function testAttribute(): void
    {
        /** @var PropertyAccessorInterface */
        $propmock = $this->createMock(PropertyAccessorInterface::class);
        $utilpmock = new ImageUtils();
        $ref = new ReflectionClass(Media::class);
        $props = $ref->getProperty('file');
        $attr = $props->getAttributes(ImageUploadProperties::class);
        $this->assertArrayHasKey('slug', $attr[0]->getArguments());
        $var = $attr[0]->getArguments();
        $imageprop = new ImageUploadProperties($var['name'], $var['slug'], $var['dimension'], $var['alt'], $var['mimeTypes'], $propmock, ['media_uploads_path' => '/media/uploads'], $utilpmock);
        $this->assertEquals('slug', $imageprop->getSlug());
        $this->assertEquals('dimension', $imageprop->getDimension());
    }
}
