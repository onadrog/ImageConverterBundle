<?php

namespace Onadrog\ImageConverterBundle\Tests\Unit;

use Onadrog\ImageConverterBundle\Entity\Media;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUploadProperties;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ImageUploadPropertiesTest extends TestCase
{
    public function testAttribute(): void
    {
        $ref = new ReflectionClass(Media::class);
        $props = $ref->getProperty('file');
        $attr = $props->getAttributes(ImageUploadProperties::class);
        $this->assertArrayHasKey('slug', $attr[0]->getArguments());
        $var = $attr[0]->getArguments();
        $imageprop = new ImageUploadProperties($var['name'], $var['slug'], $var['dimension']);
        $this->assertEquals('name', $imageprop->getImage());
        $this->assertEquals('slug', $imageprop->getSlug());
        $this->assertEquals('dimansion', $imageprop->getDimension());
    }
}
