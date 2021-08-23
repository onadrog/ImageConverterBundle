<?php

namespace Onadrog\ImageConverterBundle\Tests\Unit;

use GdImage;
use Onadrog\ImageConverterBundle\Mapping\Attribute\ImageUpload;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\DummyWithAttribute;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\Media;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\Product;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\SoloFile;
use Onadrog\ImageConverterBundle\Service\ImageUtils;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class UtilsTest extends TestCase
{
    // ----------------------- guessMappedClass method ----------------------- \\

    /**
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::guessMappedClass
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::readRelationalMapping
     * Test Entity Product Relational mapping (ManyToOne)
     * Test ImageUpload Attribute is readed from relational Mapping
     * Test ImageUploadProperties is readed from relational Mapping
     */
    public function testRelationalAttribute(): void
    {
        $array = ImageUtils::guessMappedClass(new Product(), 'media');
        $this->assertIsArray($array);
        $this->assertArrayHasKey('slug', $array);
        $this->assertInstanceOf(ReflectionClass::class, $array['entity']);
        $this->assertEquals(Media::class, $array['entity']->name);
    }

    /**
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::guessMappedClass
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::readClassAttribute
     * Test Entity SoloFile as Attribute ImageUpload and ImageUploadProperties
     */
    public function testAttribute(): void
    {
        $array = ImageUtils::guessMappedClass(new SoloFile(), 'file');
        $classAttributes = $array['entity']->getAttributes()[0]->getName();
        $this->assertInstanceOf(ReflectionClass::class, $array['entity']);
        $this->assertEquals(ImageUpload::class, $classAttributes);
        $this->assertContains('slug', $array);
    }

    /**
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::guessMappedClass
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::readRelationalMapping
     * Test without ImageUpload class Attribute nor on RelationalClass
     */
    public function testRuntimeExecption(): void
    {
        $this->expectException(RuntimeException::class);
        ImageUtils::guessMappedClass(new Product(), 'dummy');
    }

    /**
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::guessMappedClass
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::readClassAttribute
     * Test with ImageUpload class Attribute AND without ImageUploadProperties
     */
    public function testClassRuntimeExecption(): void
    {
        $this->expectException(RuntimeException::class);
        ImageUtils::guessMappedClass(new DummyWithAttribute(), 'name');
    }

    // ----------------------- namer method ----------------------- \\

    /**
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::namer
     */
    public function testNamer(): void
    {
        $utils = new ImageUtils();
        $originalNames = [
            'aaa.111.é:@',
            'bbb.111.é:@',
            'ccc.111.é:@',
        ];
        $slug = new AsciiSlugger();
        foreach ($originalNames as $name) {
            $safeName = $slug->slug($name);
            $res = $utils->namer($name, 'default');
            $this->assertEquals($safeName, $res['safename']);
            $res = $utils->namer($name, 'uuid');
            $this->assertIsArray($res);
            $this->assertArrayHasKey('slug', $res);
        }
    }

    // ----------------------- createGdImg method ----------------------- \\

    /**
     * @covers \Onadrog\ImageConverterBundle\Service\ImageUtils::createGdImg
     */
    public function testGdImage(): void
    {
        $file = function ($v) {
            return new UploadedFile(__DIR__.'/../Mock/images/'.strtoupper($v).".$v", strtoupper($v));
        };
        $utils = new ImageUtils();
        $fileName = [
            'jpg',
            'png',
            'jpeg',
        ];
        foreach ($fileName as $name) {
            $uploadedFile = $file($name);
            $path = $uploadedFile->getPathName();
            $ext = $uploadedFile->guessExtension();
            $image = $utils->createGdImg($ext, $path);
            $this->assertInstanceOf(GdImage::class, $image);
        }
    }
}
